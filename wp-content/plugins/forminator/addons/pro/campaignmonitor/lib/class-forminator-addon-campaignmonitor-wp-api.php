<?php
/**
 * The Forminator Campaign Monitor API
 *
 * @package Forminator
 */

/**
 * Class Forminator_Campaignmonitor_Wp_Api
 */
class Forminator_Campaignmonitor_Wp_Api {

	/**
	 * Instances of campaignmonitor api
	 *
	 * @var array
	 */
	private static $_instances = array();

	/**
	 * Campaignmonitor api key
	 *
	 * @var string
	 */
	private $_api_key = '';

	/**
	 * Last data sent to campaignmonitor
	 *
	 * @since 1.0 Campaignmonitor Integration
	 * @var array
	 */
	private $_last_data_sent = array();

	/**
	 * Last data received from campaignmonitor
	 *
	 * @since 1.0 Campaignmonitor Integration
	 * @var array
	 */
	private $_last_data_received = array();

	/**
	 * Last URL requested
	 *
	 * @since 1.0 Campaignmonitor Integration
	 * @var string
	 */
	private $_last_url_request = '';


	/**
	 * Base API Endpoint
	 *
	 * @var string
	 */
	private $_endpoint = 'https://api.createsend.com/api/v3.2/';

	/**
	 * Forminator_Campaignmonitor_Wp_Api constructor.
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $api_key API key.
	 *
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function __construct( $api_key ) {
		// prerequisites.
		if ( ! $api_key ) {
			throw new Forminator_Integration_Exception( esc_html__( 'Missing required API Key', 'forminator' ) );
		}

		$this->_api_key = $api_key;
	}

	/**
	 * Get singleton
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $api_key API Key.
	 *
	 * @return Forminator_Campaignmonitor_Wp_Api|null
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public static function get_instance( $api_key ) {
		if ( ! isset( self::$_instances[ md5( $api_key ) ] ) ) {
			self::$_instances[ md5( $api_key ) ] = new self( $api_key );
		}

		return self::$_instances[ md5( $api_key ) ];
	}

	/**
	 * Add custom user agent on request
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $user_agent User agent.
	 *
	 * @return string
	 */
	public function filter_user_agent( $user_agent ) {
		$user_agent .= ' ForminatorCampaignmonitor/' . FORMINATOR_ADDON_CAMPAIGNMONITOR_VERSION;

		/**
		 * Filter user agent to be used by campaignmonitor api
		 *
		 * @since 1.1
		 *
		 * @param string $user_agent current user agent.
		 */
		$user_agent = apply_filters( 'forminator_addon_campaignmonitor_api_user_agent', $user_agent );

		return $user_agent;
	}

	/**
	 * HTTP Request
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`.
	 * @param string $path Path.
	 * @param array  $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	private function request( $verb, $path, $args = array() ) {
		// Adding extra user agent for wp remote request.
		add_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		$url  = trailingslashit( $this->_endpoint ) . $path;
		$verb = ! empty( $verb ) ? $verb : 'GET';

		/**
		 * Filter campaignmonitor url to be used on sending api request
		 *
		 * @since 1.1
		 *
		 * @param string $url  full url with scheme.
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`.
		 * @param string $path requested path resource.
		 * @param array  $args argument sent to this function.
		 */
		$url = apply_filters( 'forminator_addon_campaignmonitor_api_url', $url, $verb, $path, $args );

		$this->_last_url_request = $url;

		$encoded_auth = base64_encode( $this->_api_key . ':forminator-no_pass' ); //phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		$headers      = array(
			'Authorization' => 'Basic ' . $encoded_auth,
		);

		/**
		 * Filter campaignmonitor headers to sent on api request
		 *
		 * @since 1.1
		 *
		 * @param array  $headers Headers.
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`.
		 * @param string $path requested path resource.
		 * @param array  $args argument sent to this function.
		 */
		$headers = apply_filters( 'forminator_addon_campaignmonitor_api_request_headers', $headers, $verb, $path, $args );

		$_args = array(
			'method'  => $verb,
			'headers' => $headers,
		);

		$request_data = $args;
		/**
		 * Filter campaignmonitor request data to be used on sending api request
		 *
		 * @since 1.1
		 *
		 * @param array  $request_data it will be `http_build_query`-ed when `GET` or `wp_json_encode`-ed otherwise.
		 * @param string $verb         `GET` `POST` `PUT` `DELETE` `PATCH`.
		 * @param string $path         requested path resource.
		 */
		$args = apply_filters( 'forminator_addon_campaignmonitor_api_request_data', $request_data, $verb, $path );

		if ( 'GET' === $verb || 'DELETE' === $verb ) {
			$url .= ( '?' . http_build_query( $args ) );
		} else {
			$_args['body'] = wp_json_encode( $args );
		}

		$this->_last_data_sent = $args;

		$res         = wp_remote_request( $url, $_args );
		$wp_response = $res;

		remove_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		if ( is_wp_error( $res ) || ! $res ) {
			forminator_addon_maybe_log( __METHOD__, $res );
			throw new Forminator_Integration_Exception(
				esc_html__( 'Failed to process request, make sure your Webhook URL is correct and your server has internet connection.', 'forminator' )
			);
		}

		if ( isset( $res['response']['code'] ) ) {
			$status_code = $res['response']['code'];
			$msg         = '';
			if ( $status_code >= 400 ) {
				if ( isset( $res['response']['message'] ) ) {
					$msg = $res['response']['message'];
				}

				$body_json = wp_remote_retrieve_body( $res );
				$res_json  = json_decode( $body_json );
				if ( ! is_null( $res_json ) && is_object( $res_json ) && isset( $res_json->Message ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					$msg = $res_json->Message; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				}

				if ( 404 === $status_code ) {
					throw new Forminator_Integration_Exception(
						sprintf(
						/* translators: %s: Error message */
							esc_html__( 'Failed to process request : %s', 'forminator' ),
							esc_html( $msg )
						)
					);
				}
				throw new Forminator_Integration_Exception(
					sprintf(
					/* translators: %s: Error message */
						esc_html__( 'Failed to process request : %s', 'forminator' ),
						esc_html( $msg )
					)
				);
			}
		}

		$body = wp_remote_retrieve_body( $res );

		// probably silent mode.
		if ( ! empty( $body ) ) {
			$res = json_decode( $body );
		}

		$response = $res;
		/**
		 * Filter campaignmonitor api response returned to integration
		 *
		 * @since 1.1
		 *
		 * @param mixed          $response    original wp remote request response or decoded body if available.
		 * @param string         $body        original content of http response's body.
		 * @param array|WP_Error $wp_response original wp remote request response.
		 */
		$res = apply_filters( 'forminator_addon_campaignmonitor_api_response', $response, $body, $wp_response );

		$this->_last_data_received = $res;

		forminator_addon_maybe_log( $res );

		return $res;
	}


	/**
	 * Send data to static webhook campaignmonitor URL
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function post_( $args ) {

		return $this->request(
			'POST',
			'',
			$args
		);
	}

	/**
	 * Get Primary Contact
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function get_primary_contact( $args = array() ) {
		$default_args = array();

		$args = array_merge( $default_args, $args );

		return $this->request(
			'GET',
			'primarycontact.json',
			$args
		);
	}

	/**
	 * Get Current Data on Campaign Monitor
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function get_system_date( $args = array() ) {
		$default_args = array();

		$args = array_merge( $default_args, $args );

		return $this->request(
			'GET',
			'systemdate.json',
			$args
		);
	}

	/**
	 * Get List Detail
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $list_id List Id.
	 * @param array  $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function get_list( $list_id, $args = array() ) {
		$default_args = array();

		$args = array_merge( $default_args, $args );

		return $this->request(
			'GET',
			'lists/' . rawurlencode( trim( $list_id ) ) . '.json',
			$args
		);
	}

	/**
	 * Get Lists on a Client
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $client_id Client Id.
	 * @param array  $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function get_client_lists( $client_id, $args = array() ) {
		$default_args = array();

		$args = array_merge( $default_args, $args );

		return $this->request(
			'GET',
			'clients/' . rawurlencode( trim( $client_id ) ) . '/lists.json',
			$args
		);
	}

	/**
	 * Get Clients
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function get_clients( $args = array() ) {
		$default_args = array();

		$args = array_merge( $default_args, $args );

		return $this->request(
			'GET',
			'clients.json',
			$args
		);
	}

	/**
	 * Get Client Details
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $client_id Client Id.
	 * @param array  $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function get_client( $client_id, $args = array() ) {
		$default_args = array();

		$args = array_merge( $default_args, $args );

		return $this->request(
			'GET',
			'clients/' . rawurlencode( trim( $client_id ) ) . '.json',
			$args
		);
	}

	/**
	 * Get Custom Fields on Lists
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $list_id List Id.
	 * @param array  $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function get_list_custom_field( $list_id, $args = array() ) {
		$default_args = array();

		$args = array_merge( $default_args, $args );

		return $this->request(
			'GET',
			'lists/' . rawurlencode( trim( $list_id ) ) . '/customfields.json',
			$args
		);
	}

	/**
	 * Add Subscriber to the list
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $list_id List Id.
	 * @param string $email_address Email addresses.
	 * @param array  $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function add_subscriber( $list_id, $email_address, $args = array() ) {
		$default_args = array(
			'EmailAddress' => $email_address,
		);

		$args = array_merge( $default_args, $args );

		return $this->request(
			'POST',
			'subscribers/' . rawurlencode( trim( $list_id ) ) . '.json',
			$args
		);
	}

	/**
	 * Delete Subscriber from the list
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @param string $list_id List Id.
	 * @param string $email_address Email addresses.
	 * @param array  $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function delete_subscriber( $list_id, $email_address, $args = array() ) {
		$default_args = array(
			'email' => $email_address,
		);

		$args = array_merge( $default_args, $args );

		return $this->request(
			'DELETE',
			'subscribers/' . rawurlencode( trim( $list_id ) ) . '.json',
			$args
		);
	}

	/**
	 * Get last data sent
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @return array
	 */
	public function get_last_data_sent() {
		return $this->_last_data_sent;
	}

	/**
	 * Get last data received
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @return array
	 */
	public function get_last_data_received() {
		return $this->_last_data_received;
	}

	/**
	 * Get last data received
	 *
	 * @since 1.0 Campaignmonitor Integration
	 *
	 * @return string
	 */
	public function get_last_url_request() {
		return $this->_last_url_request;
	}
}