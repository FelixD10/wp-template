<?php
namespace Aws\MediaConvert;

use Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Elemental MediaConvert** service.
 * @method \Aws\Result associateCertificate(array $args = [])
 * @method \GuzzleHttp\Promise\Promise associateCertificateAsync(array $args = [])
 * @method \Aws\Result cancelJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise cancelJobAsync(array $args = [])
 * @method \Aws\Result createJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createJobAsync(array $args = [])
 * @method \Aws\Result createJobTemplate(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createJobTemplateAsync(array $args = [])
 * @method \Aws\Result createPreset(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createPresetAsync(array $args = [])
 * @method \Aws\Result createQueue(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createQueueAsync(array $args = [])
 * @method \Aws\Result deleteJobTemplate(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteJobTemplateAsync(array $args = [])
 * @method \Aws\Result deletePolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deletePolicyAsync(array $args = [])
 * @method \Aws\Result deletePreset(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deletePresetAsync(array $args = [])
 * @method \Aws\Result deleteQueue(array $args = [])
 * @method \GuzzleHttp\Promise\Promise deleteQueueAsync(array $args = [])
 * @method \Aws\Result describeEndpoints(array $args = [])
 * @method \GuzzleHttp\Promise\Promise describeEndpointsAsync(array $args = [])
 * @method \Aws\Result disassociateCertificate(array $args = [])
 * @method \GuzzleHttp\Promise\Promise disassociateCertificateAsync(array $args = [])
 * @method \Aws\Result getJob(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getJobAsync(array $args = [])
 * @method \Aws\Result getJobTemplate(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getJobTemplateAsync(array $args = [])
 * @method \Aws\Result getPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getPolicyAsync(array $args = [])
 * @method \Aws\Result getPreset(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getPresetAsync(array $args = [])
 * @method \Aws\Result getQueue(array $args = [])
 * @method \GuzzleHttp\Promise\Promise getQueueAsync(array $args = [])
 * @method \Aws\Result listJobTemplates(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listJobTemplatesAsync(array $args = [])
 * @method \Aws\Result listJobs(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listJobsAsync(array $args = [])
 * @method \Aws\Result listPresets(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listPresetsAsync(array $args = [])
 * @method \Aws\Result listQueues(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listQueuesAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putPolicy(array $args = [])
 * @method \GuzzleHttp\Promise\Promise putPolicyAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateJobTemplate(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateJobTemplateAsync(array $args = [])
 * @method \Aws\Result updatePreset(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updatePresetAsync(array $args = [])
 * @method \Aws\Result updateQueue(array $args = [])
 * @method \GuzzleHttp\Promise\Promise updateQueueAsync(array $args = [])
 */
class MediaConvertClient extends AwsClient {}