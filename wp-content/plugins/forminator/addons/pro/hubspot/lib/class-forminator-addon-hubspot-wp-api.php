<?php
/**
 * Forminator Hubspot API
 *
 * @package Forminator
 */

/**
 * Class Forminator_Hubspot_Wp_Api
 */
class Forminator_Hubspot_Wp_Api {

	const AUTHORIZE_URL = 'https://app.hubspot.com/oauth/authorize';
	const CLIENT_ID     = 'd4c00215-5579-414c-a831-95be7218239b';

	/**
	 * OAuth scopes
	 *
	 * @var string
	 */
	public static $oauth_scopes = 'tickets crm.lists.write crm.lists.read crm.objects.contacts.write crm.objects.contacts.read crm.schemas.contacts.write crm.schemas.contacts.read';

	/**
	 * Instances of hubspot api
	 *
	 * @var array
	 */
	private static $_instances = array();

	/**
	 * HubSpot endpoint
	 *
	 * @var string
	 */
	private $_endpoint = 'https://api.hubspot.com';

	/**
	 * Last data sent to hubspot
	 *
	 * @since 1.0 HubSpot Integration
	 * @var array
	 */
	private $_last_data_sent = array();

	/**
	 * Last data received from hubspot
	 *
	 * @since 1.0 HubSpot Integration
	 * @var array
	 */
	private $_last_data_received = array();

	/**
	 * Last URL requested
	 *
	 * @since 1.0 HubSpot Integration
	 * @var string
	 */
	private $_last_url_request = '';

	/**
	 * Token
	 *
	 * @var string
	 */
	private $_token = '';

	/**
	 * Global Id
	 *
	 * @var string
	 */
	private $_global_id = '';

	/**
	 * Option name
	 *
	 * @var string
	 */
	private $option_name = 'forminator-hubspot-token';

	/**
	 * Forminator_Hubspot_Wp_Api constructor.
	 *
	 * @since 1.0 HubSpot Integration
	 *
	 * @param string $_token Token.
	 * @param string $_global_id Global Id.
	 *
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public function __construct( $_token, $_global_id ) {
		// prerequisites.
		if ( ! $_token ) {
			throw new Forminator_Integration_Exception( esc_html__( 'Missing required Token', 'forminator' ) );
		}

		$this->_token       = $_token;
		$this->_global_id   = $_global_id;
		$this->option_name .= $_global_id;
	}

	/**
	 * Clear Database
	 */
	public function clear_db() {
		delete_option( $this->option_name );
	}

	/**
	 * Get singleton
	 *
	 * @since 1.0 HubSpot Integration
	 *
	 * @param string $_token Token.
	 * @param string $global_id Global Id.
	 *
	 * @return Forminator_Hubspot_Wp_Api|null
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	public static function get_instance( $_token, $global_id ) {
		if ( ! isset( self::$_instances[ md5( $_token ) ] ) ) {
			self::$_instances[ md5( $_token ) ] = new self( $_token, $global_id );
		}

		return self::$_instances[ md5( $_token ) ];
	}

	/**
	 * Add custom user agent on request
	 *
	 * @since 1.0 HubSpot Integration
	 *
	 * @param string $user_agent User Agent.
	 *
	 * @return string
	 */
	public function filter_user_agent( $user_agent ) {
		$user_agent .= ' ForminatorHubspot/' . FORMINATOR_ADDON_HUBSPOT_VERSION;

		/**
		 * Filter user agent to be used by hubspot api
		 *
		 * @since 1.1
		 *
		 * @param string $user_agent current user agent.
		 */
		$user_agent = apply_filters( 'forminator_addon_hubspot_api_user_agent', $user_agent );

		return $user_agent;
	}

	/**
	 * HTTP Request
	 *
	 * @since 1.0 HubSpot Integration
	 *
	 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`.
	 * @param string $path Requested path.
	 * @param array  $args Arguments.
	 * @param string $access_token Access token.
	 * @param bool   $json Is Json.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Integration_Exception Throws Integration Exception.
	 */
	private function request( $verb, $path, $args, $access_token, $json = false ) {
		if ( ! is_array( $args ) ) {
			$args = array();
		}

		// Adding extra user agent for wp remote request.
		add_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		$url  = trailingslashit( $this->_endpoint ) . $path;
		$verb = ! empty( $verb ) ? $verb : 'GET';

		/**
		 * Filter hubspot url to be used on sending api request
		 *
		 * @since 1.1
		 *
		 * @param string $url full url with scheme.
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`.
		 * @param string $path requested path resource.
		 * @param array $args argument sent to this function.
		 */
		$url = apply_filters( 'forminator_addon_hubspot_api_url', $url, $verb, $path, $args );

		$this->_last_url_request = $url;

		$headers = array();
		if ( $access_token ) {
			$headers = array(
				'Authorization' => 'Bearer ' . $access_token,
			);
		}

		if ( 'GET' !== $verb && ! $json ) {
			$headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
		} else {
			$headers['Content-Type'] = 'application/json';
		}

		/**
		 * Filter hubspot headers to sent on api request
		 *
		 * @since 1.1
		 *
		 * @param array $headers
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`.
		 * @param string $path requested path resource.
		 * @param array $args argument sent to this function.
		 */
		$headers = apply_filters( 'forminator_addon_hubspot_api_request_headers', $headers, $verb, $path, $args );

		$_args = array(
			'method'  => $verb,
			'headers' => $headers,
		);

		$request_data = $args;
		/**
		 * Filter hubspot request data to be used on sending api request
		 *
		 * @since 1.1
		 *
		 * @param array $request_data it will be `http_build_query`-ed when `GET` or `wp_json_encode`-ed otherwise.
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`.
		 * @param string $path requested path resource.
		 */
		$args = apply_filters( 'forminator_addon_hubspot_api_request_data', $request_data, $verb, $path );
		if ( $json ) {
			$args = wp_json_encode( $args );
		}
		if ( 'GET' === $verb ) {
			$url .= ( '?' . http_build_query( $args ) );
		} else {
			$_args['body'] = $args;
		}

		$this->_last_data_sent = $args;
		$res                   = wp_remote_request( $url, $_args );
		$wp_response           = $res;

		$this->_last_data_received = $res;

		remove_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		if ( is_wp_error( $res ) || ! $res ) {
			throw new Forminator_Integration_Exception(
				esc_html__( 'Failed to process request, make sure your API URL is correct and your server has internet connection.', 'forminator' )
			);
		}

		if ( isset( $res['response']['code'] ) ) {
			$status_code = $res['response']['code'];
			$msg         = '';
			if ( $status_code > 400 ) {
				if ( isset( $res['response']['message'] ) ) {
					$msg = $res['response']['message'];
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

			$this->_last_data_received = $res;
			if ( isset( $res->status ) && 'error' === $res->status ) {
				$message = isset( $res->message ) ? $res->message : esc_html__( 'Invalid', 'forminator' );
				throw new Forminator_Integration_Exception(
					sprintf(
					/* translators: %s: Error message */
						esc_html__( 'Failed to process request : %s', 'forminator' ),
						esc_html( $message )
					)
				);
			}
			if ( isset( $res->ok ) && false === $res->ok ) {
				$msg = '';
				if ( isset( $res->error ) ) {
					$msg = $res->error;
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

		$response = $res;
		/**
		 * Filter hubspot api response returned to integration
		 *
		 * @since 1.1
		 *
		 * @param mixed $response original wp remote request response or decoded body if available.
		 * @param string $body original content of http response's body.
		 * @param array|WP_Error $wp_response original wp remote request response.
		 */
		$res = apply_filters( 'forminator_addon_hubspot_api_response', $response, $body, $wp_response );

		$this->_last_data_received = $res;

		return $res;
	}

	/**
	 * Get last data sent
	 *
	 * @since 1.0 HubSpot Integration
	 *
	 * @return array
	 */
	public function get_last_data_sent() {
		return $this->_last_data_sent;
	}

	/**
	 * Get last data received
	 *
	 * @since 1.0 HubSpot Integration
	 *
	 * @return array
	 */
	public function get_last_data_received() {
		return $this->_last_data_received;
	}

	/**
	 * Get last data received
	 *
	 * @since 1.0 HubSpot Integration
	 *
	 * @return string
	 */
	public function get_last_url_request() {
		return $this->_last_url_request;
	}

	/**
	 * Get stored token data.
	 *
	 * @return array|null
	 */
	public function get_auth_token() {
		return get_option( $this->option_name );
	}

	/**
	 * Update token data.
	 *
	 * @param array $token Token.
	 *
	 * @return void
	 */
	public function update_auth_token( array $token ) {
		update_option( $this->option_name, $token );
	}

	/**
	 * Is authorized
	 *
	 * @return array|bool|mixed|object
	 */
	public function is_authorized() {
		$auth = $this->get_auth_token();

		return ! empty( $auth['expires_in'] ) && time() < $auth['expires_in'];
	}

	/**
	 * Rfresh access token
	 *
	 * @return array|mixed|object
	 */
	public function refresh_access_token() {
		$args     = array(
			'grant_type'    => 'refresh_token',
			'refresh_token' => $this->get_token( 'refresh_token' ),
		);
		$response = $this->get_access_token( $args );

		if ( ! empty( $response->access_token ) ) {
			return $response->access_token;
		}

		return false;
	}

	/**
	 * Get token
	 *
	 * @param string $key Key.
	 *
	 * @return bool|mixed
	 */
	public function get_token( $key ) {
		$auth = $this->get_auth_token();

		if ( ! empty( $auth ) && ! empty( $auth[ $key ] ) ) {
			return $auth[ $key ];
		}

		return false;
	}

	/**
	 * Get the current token's information.
	 *
	 * @return array|mixed|object
	 */
	public function get_access_token_information() {

		$user  = '';
		$token = $this->get_token( 'access_token' );

		if ( ! empty( $token ) ) {
			$response = $this->send_authenticated( 'GET', 'oauth/v1/access-tokens/' . $token );
			if ( ! is_wp_error( $response ) ) {
				$user = $response->user;
			}
		}

		return $user;
	}

	/**
	 * Get access token
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 * @throws Forminator_Hubspot_Wp_Api_Exception Throws Integration Exception.
	 */
	public function get_access_token( $args = array() ) {
		$default_args = array(
			'grant_type' => 'authorization_code',
			'state'      => 'state', // It's added just because state param is required on the final endpoint. It's unuseful here.
		);
		$args         = array_merge( $default_args, $args );

		$url = Forminator_Hubspot::redirect_uri(
			'hubspot',
			'get_access_token',
			$args
		);

		$res      = wp_remote_get( $url );
		$body     = is_wp_error( $res ) || ! $res ? '' : wp_remote_retrieve_body( $res );
		$response = $body ? json_decode( $body ) : '';
		if ( ! empty( $response->refresh_token ) ) {
			$token_data = get_object_vars( $response );

			$token_data['expires_in'] += time();

			// Update auth token.
			$this->update_auth_token( $token_data );
		} elseif ( isset( $response->error ) ) {
			if ( 'failed_request' === $response->error ) {
				$error = esc_html__( 'Failed to process request, make sure your API URL is correct and your server has internet connection.', 'forminator' );
			} else {
				/* Translators: 1. Error message. */
				$error = sprintf( esc_html__( 'Failed to process request : %s', 'forminator' ), esc_html( $response->error ) );
			}

			throw new Forminator_Hubspot_Wp_Api_Exception( $error ); // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- Exception message is already escaped.
		}

		return $response;
	}

	/**
	 * Helper function to send authenticated Post request.
	 *
	 * @param string $verb Request type.
	 * @param string $end_point Request URL.
	 * @param array  $query_args Arguments.
	 * @param bool   $json Is Json.
	 *
	 * @return array|mixed|object
	 */
	public function send_authenticated( $verb, $end_point, $query_args = array(), $json = false ) {
		if ( $this->is_authorized() ) {
			$access_token = $this->get_token( 'access_token' );
		} else {
			$access_token = $this->refresh_access_token();
		}

		return $this->request( $verb, $end_point, $query_args, $access_token, $json );
	}

	/**
	 * Get Contact list
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 */
	public function get_contact_list( $args = array() ) {
		$default_args = array(
			'count'  => 200,
			'offset' => 0,
		);
		$args         = array_merge( $default_args, $args );
		$response     = $this->send_authenticated( 'GET', 'contacts/v1/lists/static', $args );

		return $response;
	}

	/**
	 * Add contact subscriber to HubSpot.
	 *
	 * @param array $data Contact Data.
	 *
	 * @return array|mixed|object
	 */
	public function add_update_contact( $data ) {
		$props = array();

		foreach ( $data as $key => $value ) {

			$props[] = array(
				'property' => $key,
				'value'    => $value,
			);
		}
		$email    = ! empty( $data['email'] ) ? $data['email'] : '';
		$args     = array( 'properties' => $props );
		$endpoint = 'contacts/v1/contact/createOrUpdate/email/' . $email;

		$response = $this->send_authenticated( 'POST', $endpoint, $args, true );

		if ( ! is_wp_error( $response ) && ! empty( $response->vid ) ) {
			return $response->vid;
		}

		return $response;
	}

	/**
	 * Add contact subscriber to HubSpot.
	 *
	 * @param array $data Contact Data.
	 *
	 * @return array|mixed|object
	 */
	public function delete_contact( $data ) {
		$args     = array();
		$endpoint = 'contacts/v1/contact/vid/' . $data;

		$response = $this->send_authenticated( 'DELETE', $endpoint, $args, true );

		return $response;
	}

	/**
	 * Add contact to contact list.
	 *
	 * @param string $contact_id Contact id.
	 * @param string $email Email.
	 * @param string $email_list Email list.
	 *
	 * @return array|mixed|object
	 */
	public function add_to_contact_list( $contact_id, $email, $email_list ) {
		$args     = array(
			'listId' => $email_list,
			'vids'   => array( $contact_id ),
			'emails' => array( $email ),
		);
		$endpoint = 'contacts/v1/lists/' . $email_list . '/add';

		$response = $this->send_authenticated( 'POST', $endpoint, $args, true );

		if ( ! is_wp_error( $response ) && ! empty( $response->updated ) ) {
			return true;
		}

		if ( ! empty( $response->status ) && 'error' === $response->status && ! empty( $response->message ) ) {
			$response = new WP_Error( 'provider_error', $response->message );
		}

		return $response;
	}

	/**
	 * Get Pipeline list
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 */
	public function get_pipeline( $args = array() ) {
		$response = $this->send_authenticated( 'GET', 'crm-pipelines/v1/pipelines/tickets', $args );

		return $response;
	}

	/**
	 * Add contact to contact list.
	 *
	 * @param array $ticket Tickets.
	 *
	 * @return array|mixed|object
	 */
	public function create_ticket( $ticket ) {
		$args    = array();
		$request = array(
			'subject'           => $ticket['ticket_name'],
			'content'           => $ticket['ticket_description'],
			'hs_pipeline'       => $ticket['pipeline_id'],
			'hs_pipeline_stage' => $ticket['status_id'],
			'hs_file_upload'    => $ticket['supported_file'],
		);
		$i       = 0;
		foreach ( $request as $key => $value ) {
			$args[ $i ]['name']  = $key;
			$args[ $i ]['value'] = $value;
			++$i;
		}
		$endpoint = 'crm-objects/v1/objects/tickets';
		$response = $this->send_authenticated( 'POST', $endpoint, $args, true );

		if ( ! is_wp_error( $response ) && ! empty( $response->objectId ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			return $response->objectId; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}

		return $response;
	}

	/**
	 * Associate ticket with contact
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 */
	public function ticket_associate_contact( $args = array() ) {
		$default_args = array(
			'category'     => 'HUBSPOT_DEFINED',
			'definitionId' => 16,
		);
		$args         = array_merge( $default_args, $args );

		$endpoint = 'crm-associations/v1/associations';
		$response = $this->send_authenticated( 'PUT', $endpoint, $args, true );

		return $response;
	}

	/**
	 * Delete Tickets
	 *
	 * @param array $data Data to delete.
	 *
	 * @return array|mixed|object
	 */
	public function delete_ticket( $data ) {
		$args     = array();
		$endpoint = 'crm-objects/v1/objects/tickets/' . $data;

		$response = $this->send_authenticated( 'DELETE', $endpoint, $args, true );

		return $response;
	}

	/**
	 * Get Properties list
	 *
	 * @param array $args Arguments.
	 *
	 * @return array|mixed|object
	 */
	public function get_properties( $args = array() ) {
		$response = $this->send_authenticated( 'GET', 'properties/v1/contacts/properties', $args );

		return $response;
	}

	/**
	 * Get Property of field
	 *
	 * @param string $property Property.
	 * @param string $field Field.
	 * @param array  $args Arguments.
	 *
	 * @return array|mixed|object
	 */
	public function get_property( $property, $field, $args ) {
		$response = $this->send_authenticated( 'GET', 'properties/v1/contacts/properties/named/' . $field, $args );

		if ( property_exists( $response, $property ) ) {
			return $response->$property;
		} else {
			return esc_html__( 'Property does not exist', 'forminator' );
		}
	}
}