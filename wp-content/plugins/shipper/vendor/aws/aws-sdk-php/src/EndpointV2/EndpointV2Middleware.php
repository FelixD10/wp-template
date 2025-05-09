<?php
namespace Aws\EndpointV2;

use Aws\Api\Operation;
use Aws\Api\Service;
use Aws\CommandInterface;
use Closure;
use GuzzleHttp\Promise\Promise;

/**
 * Handles endpoint rule evaluation and endpoint resolution.
 *
 * IMPORTANT: this middleware must be added to the "build" step.
 * Specifically, it must precede the 'builder' step.
 *
 * @internal
 */
class EndpointV2Middleware
{
    private static $validAuthSchemes = [
        'sigv4' => true,
        'sigv4a' => true,
        'none' => true,
        'bearer' => true,
        'sigv4-s3express' => true
    ];

    /** @var callable */
    private $nextHandler;

    /** @var EndpointProviderV2 */
    private $endpointProvider;

    /** @var Service */
    private $api;

    /** @var array */
    private $clientArgs;

    /**
     * Create a middleware wrapper function
     *
     * @param EndpointProviderV2 $endpointProvider
     * @param Service $api
     * @param array $args
     *
     * @return Closure
     */
    public static function wrap(
        EndpointProviderV2 $endpointProvider,
        Service $api,
        array $args
    ) : Closure
    {
        return function (callable $handler) use ($endpointProvider, $api, $args) {
            return new self($handler, $endpointProvider, $api, $args);
        };
    }

    /**
     * @param callable $nextHandler
     * @param EndpointProviderV2 $endpointProvider
     * @param Service $api
     * @param array $args
     */
    public function __construct(
        callable $nextHandler,
        EndpointProviderV2 $endpointProvider,
        Service $api,
        array $args
    )
    {
        $this->nextHandler = $nextHandler;
        $this->endpointProvider = $endpointProvider;
        $this->api = $api;
        $this->clientArgs = $args;
    }

    /**
     * @param CommandInterface $command
     *
     * @return Promise
     */
    public function __invoke(CommandInterface $command)
    {
        $nextHandler = $this->nextHandler;
        $operation = $this->api->getOperation($command->getName());
        $commandArgs = $command->toArray();

        $providerArgs = $this->resolveArgs($commandArgs, $operation);
        $endpoint = $this->endpointProvider->resolveEndpoint($providerArgs);

        if (!empty($authSchemes = $endpoint->getProperty('authSchemes'))) {
            $this->applyAuthScheme(
                $authSchemes,
                $command
            );
        }

        return $nextHandler($command, $endpoint);
    }

    /**
     * Resolves client, context params, static context params and endpoint provider
     * arguments provided at the command level.
     *
     * @param array $commandArgs
     * @param Operation $operation
     *
     * @return array
     */
    private function resolveArgs(array $commandArgs, Operation $operation) : array
    {
        $rulesetParams = $this->endpointProvider->getRuleset()->getParameters();
        $endpointCommandArgs = $this->filterEndpointCommandArgs(
            $rulesetParams,
            $commandArgs
        );
        $staticContextParams = $this->bindStaticContextParams(
            $operation->getStaticContextParams()
        );
        $contextParams = $this->bindContextParams(
            $commandArgs, $operation->getContextParams()
        );

        return array_merge(
            $this->clientArgs,
            $contextParams,
            $staticContextParams,
            $endpointCommandArgs
        );
    }

    /**
     * Compares Ruleset parameters against Command arguments
     * to create a mapping of arguments to pass into the
     * endpoint provider for endpoint resolution.
     *
     * @param array $rulesetParams
     * @param array $commandArgs
     * @return array
     */
    private function filterEndpointCommandArgs(
        array $rulesetParams,
        array $commandArgs
    ) : array
    {
        $endpointMiddlewareOpts = [
            '@use_dual_stack_endpoint' => 'UseDualStack',
            '@use_accelerate_endpoint' => 'Accelerate',
            '@use_path_style_endpoint' => 'ForcePathStyle'
        ];

        $filteredArgs = [];

        foreach($rulesetParams as $name => $value) {
            if (isset($commandArgs[$name])) {
                if (!empty($value->getBuiltIn())) {
                    continue;
                }
                $filteredArgs[$name] = $commandArgs[$name];
            }
        }

        if ($this->api->getServiceName() === 's3') {
            foreach($endpointMiddlewareOpts as $optionName => $newValue) {
                if (isset($commandArgs[$optionName])) {
                    $filteredArgs[$newValue] = $commandArgs[$optionName];
                }
            }
        }

        return $filteredArgs;
    }

    /**
     * Binds static context params to their corresponding values.
     *
     * @param $staticContextParams
     *
     * @return array
     */
    private function bindStaticContextParams($staticContextParams) : array
    {
        $scopedParams = [];

        forEach($staticContextParams as $paramName => $paramValue) {
            $scopedParams[$paramName] = $paramValue['value'];
        }

        return $scopedParams;
    }

    /**
     * Binds context params to their corresponding values found in
     * command arguments.
     *
     * @param array $commandArgs
     * @param array $contextParams
     *
     * @return array
     */
    private function bindContextParams(
        array $commandArgs,
        array $contextParams
    ) : array
    {
        $scopedParams = [];

        foreach($contextParams as $name => $spec) {
            if (isset($commandArgs[$spec['shape']])) {
                $scopedParams[$name] = $commandArgs[$spec['shape']];
            }
        }

        return $scopedParams;
    }

    /**
     * Applies resolved auth schemes to the command object.
     *
     * @param $authSchemes
     * @param $command
     *
     * @return void
     */
    private function applyAuthScheme(
        array $authSchemes,
        CommandInterface $command
    ) : void
    {
        $authScheme = $this->resolveAuthScheme($authSchemes);
        $command->setAuthSchemes($authScheme);
    }

    /**
     * Returns the first compatible auth scheme in an endpoint object's
     * auth schemes.
     *
     * @param array $authSchemes
     *
     * @return array
     */
    private function resolveAuthScheme(array $authSchemes) : array
    {
        $invalidAuthSchemes = [];

        foreach($authSchemes as $authScheme) {
            if (isset(self::$validAuthSchemes[$authScheme['name']])) {
                return $this->normalizeAuthScheme($authScheme);
            } else {
                $invalidAuthSchemes[] = "`{$authScheme['name']}`";
            }
        }

        $invalidAuthSchemesString = implode(', ', $invalidAuthSchemes);
        $validAuthSchemesString = '`'
            . implode('`, `', array_keys(self::$validAuthSchemes))
            . '`';
        throw new \InvalidArgumentException(
            "This operation requests {$invalidAuthSchemesString}"
            . " auth schemes, but the client only supports {$validAuthSchemesString}."
        );
    }

    /**
     * Normalizes an auth scheme's name, signing region or signing region set
     * to the auth keys recognized by the SDK.
     *
     * @param array $authScheme
     * @return array
     */
    private function normalizeAuthScheme(array $authScheme) : array
    {
        /*
            sigv4a will contain a regionSet property. which is guaranteed to be `*`
            for now.  The SigV4 class handles this automatically for now. It seems
            complexity will be added here in the future.
       */
        $normalizedAuthScheme = [];

        if (isset($authScheme['disableDoubleEncoding'])
            && $authScheme['disableDoubleEncoding'] === true
            && $authScheme['name'] !== 'sigv4a'
            && $authScheme['name'] !== 'sigv4-s3express'
        ) {
            $normalizedAuthScheme['version'] = 's3v4';
        } elseif ($authScheme['name'] === 'none') {
            $normalizedAuthScheme['version'] = 'anonymous';
        }
        else {
            $normalizedAuthScheme['version'] = str_replace(
                'sig', '', $authScheme['name']
            );
        }

        $normalizedAuthScheme['name'] = isset($authScheme['signingName']) ?
            $authScheme['signingName'] : null;
        $normalizedAuthScheme['region'] = isset($authScheme['signingRegion']) ?
            $authScheme['signingRegion'] : null;
        $normalizedAuthScheme['signingRegionSet'] = isset($authScheme['signingRegionSet']) ?
            $authScheme['signingRegionSet'] : null;

        return $normalizedAuthScheme;
    }
}