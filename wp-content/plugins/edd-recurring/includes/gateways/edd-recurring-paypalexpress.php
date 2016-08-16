<?php
/**
 * PayPal Express Recurring Gateway
 *
 * Relevant Links (PayPal makes it tough to find them)
 *
 * CreateRecurringPaymentsProfile API Operation (NVP) - https://developer.paypal.com/docs/classic/api/merchant/CreateRecurringPaymentsProfile_API_Operation_NVP/
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $edd_recurring_paypal_express;

class EDD_Recurring_PayPal_Express extends EDD_Recurring_Gateway {

	private $api_endpoint;
	private $checkout_url;
	protected $username;
	protected $password;
	protected $signature;

	/**
	 * Get things rollin'
	 *
	 * @since 2.4
	 */
	public function init() {

		$this->id = 'paypalexpress';
		$this->friendly_name = __( 'PayPal Express', 'edd-recurring' );

		$this->offsite = true;

		if ( edd_is_test_mode() ) {
			$this->api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			$this->checkout_url = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
		} else {
			$this->api_endpoint = 'https://api-3t.paypal.com/nvp';
			$this->checkout_url = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
		}

		$creds = edd_recurring_get_paypal_api_credentials();

		$this->username  = $creds['username'];
		$this->password  = $creds['password'];
		$this->signature = $creds['signature'];

		add_action( 'template_redirect', array( $this, 'process_confirmation' ), -99999 );
		add_action( 'http_api_curl', array( $this, 'alter_paypal_curl_ssl_version' ), 10, 3 );
		add_action( 'edd_pre_refund_payment', array( $this, 'process_refund' ) );

	}

	/**
	 * Upgrades the TLS for calls to the PayPal API via the WordPress HTTP API
	 * @param  reference boject $handle The cURL object
	 * @param  array            $r      Array of parameters for the WP_HTTP class
	 * @param  string           $url    The URL being called by the WP_HTTP class
	 * @return void
	 */
	public function alter_paypal_curl_ssl_version( $handle, $r, $url ) {
		if ( false !== strpos( $url, $this->api_endpoint ) ) {
			curl_setopt( $handle, CURLOPT_SSLVERSION, 6 ); // 6 is TLS 1.2
		}
	}

	/**
	 * Validate Fields
	 *
	 * @description: Validate additional fields during checkout submission
	 *
	 * @since      2.4
	 *
	 * @param $data
	 * @param $posted
	 */
	public function validate_fields( $data, $posted ) {

		if ( empty( $this->username ) || empty( $this->password ) || empty( $this->signature ) ) {
			edd_set_error( 'edd_recurring_no_paypal_api', __( 'It appears that you have not configured PayPal API access. Please configure it in EDD &rarr; Settings', 'edd_recurring' ) );
		}

		if( edd_get_option( 'paypal_in_context' ) ) {
			edd_set_error( 'edd_recurring_in_context_', __( 'In-Context checkout is not permitted for recurring payments in PayPal Express.', 'edd-recurring') );
		}

	}

	/**
	 * Create payment profiles
	 *
	 * @since 2.4
	 */
	public function create_payment_profiles() {

		foreach( $this->subscriptions as $key => $subscription ) {

			// This is a temporary ID used to look it up later during IPN processing
			$this->subscriptions[ $key ]['profile_id'] = 'ppe-' . $this->purchase_data['purchase_key'] . '-' . $subscription['id'];

		}

	}

	/**
	 * Redirect to PayPal
	 *
	 * @since 2.4
	 */
	public function complete_signup() {

		$payment      = new EDD_Payment( $this->payment_id );
		$item_titles  = array();
		$item_total   = 0;
		$tax          = 0;

		foreach( $this->subscriptions as $subscription ) {

			$item_titles[] = html_entity_decode( $subscription['name'], ENT_COMPAT, 'UTF-8' );
			$item_total   += $subscription['initial_amount'] - $subscription['initial_tax'];
			$tax          += $subscription['initial_tax'];

		}

		$description = implode( ', ', $item_titles );

		$args = array(
			'USER'                          => $this->username,
			'PWD'                           => $this->password,
			'SIGNATURE'                     => $this->signature,
			'VERSION'                       => '124',
			'METHOD'                        => 'SetExpressCheckout',
			'EMAIL'                         => $this->email,
			'RETURNURL'                     => add_query_arg( array( 'edd-confirm' => 'paypal_express', 'payment_id' => $this->payment_id ), edd_get_success_page_uri() ),
			'CANCELURL'                     => edd_get_failed_transaction_uri(),
			'REQCONFIRMSHIPPING'            => 0,
			'NOSHIPPING'                    => 1,
			'ALLOWNOTE'                     => 0,
			'ADDROVERRIDE'                  => 0,
			'PAGESTYLE'                     => edd_get_option( 'paypal_page_style', '' ),
			'SOLUTIONTYPE'                  => 'Sole',
			'LANDINGPAGE'                   => 'Billing',
			'PAYMENTREQUEST_0_AMT'          => round( $payment->total, 2),
			'PAYMENTREQUEST_0_ITEMAMT'      => round( $item_total, 2 ),
			'PAYMENTREQUEST_0_TAXAMT'       => round( $tax, 2 ),
			'PAYMENTREQUEST_0_CURRENCYCODE' => strtoupper( edd_get_currency() ),
			'PAYMENTREQUEST_0_DESC'         => $description,
			'L_PAYMENTREQUEST_0_AMT0'       => round( $item_total, 2 ),
			'L_PAYMENTREQUEST_0_NAME0'      => $description,
			'L_PAYMENTREQUEST_0_NUMBER0'    => 1,
			'L_PAYMENTREQUEST_0_QTY0'       => 1,
		);

		foreach ( $this->subscriptions as $key => $subscription ) {

			$args[ 'L_BILLINGAGREEMENTDESCRIPTION' . $key ] = wp_specialchars_decode( get_the_title( $subscription['id'] ), ENT_QUOTES );
			$args[ 'L_BILLINGTYPE' . $key ]                 = 'RecurringPayments';

		}

		$request = wp_remote_post( $this->api_endpoint, array( 'timeout' => 45, 'sslverify' => false, 'httpversion' => '1.1', 'body' => $args ) );
		$body    = wp_remote_retrieve_body( $request );
		$code    = wp_remote_retrieve_response_code( $request );
		$message = wp_remote_retrieve_response_message( $request );

		if( is_wp_error( $request ) ) {

			edd_set_error( 'edd_recurring_ppe_general', $request->get_error_message() );

		} elseif ( 200 == $code && 'OK' == $message ) {

			if( is_string( $body ) ) {
				wp_parse_str( $body, $body );
			}

			if( 'failure' === strtolower( $body['ACK'] ) ) {

				edd_set_error( $body['L_ERRORCODE0'], $body['L_ERRORCODE0'] . ': ' . $body['L_LONGMESSAGE0'] );

			} else {

				// Successful token
				wp_redirect( $this->checkout_url . $body['TOKEN'] );
				exit;

			}

		} else {

			edd_set_error( 'edd_recurring_ppe_general', __( 'Something has gone wrong, please try again', 'edd-recurring' ) );

		}

	}

	/**
	 * Process payment confirmation after returning from PayPal
	 *
	 * @since 2.1
	 */
	public function process_confirmation() {

		if( ! edd_is_success_page() ) {
			return;
		}

		if( empty( $_GET['edd-confirm'] ) ) {
			return;
		}

		$auto_confirm = edd_get_option( 'pp_auto_confirm', false );
		if ( isset( $_POST['confirmation'] ) && isset( $_POST['edd_ppe_confirm_nonce'] ) && wp_verify_nonce( $_POST['edd_ppe_confirm_nonce'], 'edd-ppe-confirm-nonce' ) && ( isset( $_GET['payment_id'] ) && is_numeric( $_GET['payment_id'] ) ) || $auto_confirm ) {
			$token      = $auto_confirm ? $_GET['token'] : $_POST['token'];
			$payment_id = $auto_confirm ? absint( $_GET['payment_id'] ) : absint( $_POST['payment_id'] );
			$details    = $this->get_checkout_details( $token );
			$payer_id   = $details['PAYERID'];
			$payment    = new EDD_Payment( $payment_id );

			// Process the actual payment with DoExpressCheckoutPayment
			$do_args = array(
				'USER'                           => $this->username,
				'PWD'                            => $this->password,
				'SIGNATURE'                      => $this->signature,
				'VERSION'                        => '124',
				'TOKEN'                          => $token,
				'METHOD'                         => 'DoExpressCheckoutPayment',
				'PAYERID'                        => $payer_id,
				'PAYMENTREQUEST_0_PAYMENTACTION' => 'SALE',
				'PAYMENTREQUEST_0_CUSTOM'        => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' - ' . __( 'Subscriptions', 'edd-recurring' ),
				'PAYMENTREQUEST_0_AMT'           => round( $payment->total, 2 ),
				'PAYMENTREQUEST_0_CURRENCYCODE'  => strtoupper( edd_get_currency() ),
			);

			$do_request = wp_remote_post( $this->api_endpoint, array( 'timeout' => 45, 'sslverify' => false, 'httpversion' => '1.1', 'body' => $do_args ) );
			$do_body    = wp_remote_retrieve_body( $do_request );
			$code       = wp_remote_retrieve_response_code( $do_request );
			$message    = wp_remote_retrieve_response_message( $do_request );

			if ( is_wp_error( $do_request ) ) {

				$error = '<p>' . __( 'An unidentified error occurred.', 'edd-recurring' ) . '</p>';
				$error .= '<p>' . $do_request->get_error_message() . '</p>';

				wp_die( $error, __( 'Error', 'edd-recurring' ), array( 'response' => '401' ) );

			} elseif ( 200 == $code && 'OK' == $message ) {

				if( is_string( $do_body ) ) {
					wp_parse_str( $do_body, $do_body );
				}

				if( 'failure' === strtolower( $do_body['ACK'] ) ) {

					edd_record_gateway_error( __( 'PayPal Express Error', 'edd-recurring' ), sprintf( __( 'Error processing payment: %s', 'edd-recurring' ), json_encode( $do_body ) . json_encode( $do_args ) ) );

					// Catch invalid payment, redirect back to PayPal
					if ( 10486 === (int) $do_body['L_ERRORCODE0'] ) {
						wp_redirect( $this->checkout_url . $token );
						die();
					}

					edd_set_error( $do_body['L_ERRORCODE0'], $do_body['L_LONGMESSAGE0'] );

					// get rid of the pending purchase
					edd_update_payment_status( $payment_id, 'failed' );

					//Send back to checkout
					edd_send_back_to_checkout( '?payment-mode=' . $this->id );

				} else {

					foreach( $details['subscriptions'] as $subscription ) {

						// Successful payment, now create the recurring profile

						$args = array(
							'USER'                    => $this->username,
							'PWD'                     => $this->password,
							'SIGNATURE'               => $this->signature,
							'VERSION'                 => '124',
							'TOKEN'                   => $token,
							'PROFILEREFERENCE'        => $subscription->id,
							'METHOD'                  => 'CreateRecurringPaymentsProfile',
							'PROFILESTARTDATE'        => date( 'Y-m-d\Tg:i:s', strtotime( $subscription->expiration, current_time( 'timestamp' ) ) ),
							'BILLINGPERIOD'           => ucwords( $subscription->period ),
							'BILLINGFREQUENCY'        => 1,
							'AMT'                     => round( $subscription->recurring_amount, 2),
							'TOTALBILLINGCYCLES'      => $subscription->bill_times > 1 ? $subscription->bill_times - 1 : $subscription->bill_times,
							'CURRENCYCODE'            => $details['CURRENCYCODE'],
							'FAILEDINITAMTACTION'     => 'CancelOnFailure',
							'L_BILLINGTYPE0'          => 'RecurringPayments',
							'DESC'                    => wp_specialchars_decode( get_the_title( $subscription->product_id ), ENT_QUOTES ),
							'BUTTONSOURCE'            => 'EasyDigitalDownloads_SP',
						);

						$request = wp_remote_post( $this->api_endpoint, array( 'timeout' => 45, 'sslverify' => false, 'httpversion' => '1.1', 'body' => $args ) );
						$body    = wp_remote_retrieve_body( $request );
						$code    = wp_remote_retrieve_response_code( $request );
						$message = wp_remote_retrieve_response_message( $request );

						if( is_wp_error( $request ) ) {

							$error = '<p>' . __( 'An unidentified error occurred.', 'edd-recurring' ) . '</p>';
							$error .= '<p>' . $request->get_error_message() . '</p>';

							wp_die( $error, __( 'Error', 'edd-recurring' ), array( 'response' => '401' ) );

						} elseif ( 200 == $code && 'OK' == $message ) {

							if( is_string( $body ) ) {
								wp_parse_str( $body, $body );
							}

							if( 'failure' === strtolower( $body['ACK'] ) ) {

								/*
								 * PayPal's API appears to have a bug that causes a failure to be reported here sometimes even when the subscription is successfully created.
								 * To get around this, we call the PayPal API to retrieve the details of the subscription to verify if it was created.
								 *
								 * See https://github.com/easydigitaldownloads/edd-recurring/issues/279
								 *
								 */

								if( ! empty( $body['PROFILEID'] ) ) {

									// The profile ID hasn't been saved to the subscription yet, so we have to manually set it

									$subscription->profile_id = $body['PROFILEID'];
								}

								$sub = $this->get_subscription_details( $subscription );

								if( empty( $sub['error'] ) ) {

									$txn_id = ! empty( $do_body['PAYMENTINFO_0_TRANSACTIONID'] ) ? $do_body['PAYMENTINFO_0_TRANSACTIONID'] : '';

									$subscription->update( array(
										'profile_id'     => $body['PROFILEID'],
										'status'         => $sub['status'],
										'transaction_id' => $txn_id
									) );

								} else {

									edd_record_gateway_error( __( 'PayPal Express Error', 'edd-recurring' ), sprintf( __( 'Error creating payment profile: %s', 'edd-recurring' ), json_encode( $body ) . json_encode( $args ) ) );

									edd_set_error( $body['L_ERRORCODE0'], $body['L_LONGMESSAGE0'] );

									if( is_wp_error( $sub['error'] ) ) {

										edd_insert_payment_note( $payment_id, $sub['error']->get_error_message() );

									}

									// Send back to checkout
									edd_send_back_to_checkout( '?payment-mode=' . $this->id );

								}


							} else {

								// Successful subscription
								if ( 'ActiveProfile' === $body['PROFILESTATUS'] || ( 'PendingProfile' === $body['PROFILESTATUS'] && edd_is_test_mode() ) ) {

									$subscription->update( array( 'profile_id' => $body['PROFILEID'], 'status' => 'active' ) );

								} else {

									$subscription->update( array( 'profile_id' => $body['PROFILEID'] ) );

								}

							}

						} else {

							edd_record_gateway_error( __( 'PayPal Express Error', 'edd-recurring' ), sprintf( __( 'Error creating payment profile: %s', 'edd-recurring' ), json_encode( $body ) . json_encode( $args ) ) );

							edd_set_error( $body['L_ERRORCODE0'], $body['L_LONGMESSAGE0'] );

							// get rid of the pending purchase
							edd_update_payment_status( $payment_id, 'failed' );

							//Send back to checkout
							edd_send_back_to_checkout( '?payment-mode=' . $this->id );

						}

					}

					if( ! empty( $do_body['PAYMENTINFO_0_TRANSACTIONID'] ) ) {
						edd_set_payment_transaction_id( $payment_id, $do_body['PAYMENTINFO_0_TRANSACTIONID'] );
					}

				}

			}

			edd_update_payment_status( $payment_id, 'publish' );

			wp_redirect( edd_get_success_page_uri() ); exit;

		} elseif ( ! empty( $_GET['token'] ) && ! empty( $_GET['payment_id'] ) ) {

			add_filter( 'the_content', array( $this, 'confirmation_form' ), 9999999 );

		}

	}

	/**
	 * Display the confirmation form
	 *
	 * @since 2.4
	 * @return string
	 */
	public function confirmation_form() {

		global $edd_checkout_details;

		$token                = sanitize_text_field( $_GET['token'] );
		$edd_checkout_details = $this->get_checkout_details( $token );

		ob_start();
		edd_get_template_part( 'paypal-express-confirm' );
		return ob_get_clean();
	}

	/**
	 * Retrieve checkout details from PayPal
	 *
	 * @since 2.4
	 * @return string
	 */
	public function get_checkout_details( $token = '' ) {

		$args = array(
			'USER'      => $this->username,
			'PWD'       => $this->password,
			'SIGNATURE' => $this->signature,
			'VERSION'   => '124',
			'METHOD'    => 'GetExpressCheckoutDetails',
			'TOKEN'     => $token
		);

		$request = wp_remote_get( add_query_arg( $args, $this->api_endpoint ), array( 'timeout' => 45, 'sslverify' => false, 'httpversion' => '1.1' ) );
		$body    = wp_remote_retrieve_body( $request );
		$code    = wp_remote_retrieve_response_code( $request );
		$message = wp_remote_retrieve_response_message( $request );

		if( is_wp_error( $request ) ) {

			return $request;

		} elseif ( 200 == $code && 'OK' == $message ) {

			if( is_string( $body ) ) {
				wp_parse_str( $body, $body );
			}

			$db = new EDD_Subscriptions_DB;
			$body['subscriptions'] = $db->get_subscriptions( array( 'parent_payment_id' => $_GET['payment_id'] ) );

			$payment = new EDD_Payment( $_GET['payment_id'] );
			$body['payment_key']   = $payment->key;

			return $body;

		}

		return false;

	}

	/**
	 * Process webhooks
	 *
	 * @since 2.4
	 */
	public function process_webhooks() {

		if ( empty( $_GET['edd-listener'] ) || ( $this->id !== $_GET['edd-listener'] && 'eppe' !== $_GET['edd-listener'] ) ) {
			return;
		}

		nocache_headers();

		$verified = false;

		// Set initial post data to empty string
		$post_data = '';

		// Fallback just in case post_max_size is lower than needed
		if ( ini_get( 'allow_url_fopen' ) ) {
			$post_data = file_get_contents( 'php://input' );
		} else {
			// If allow_url_fopen is not enabled, then make sure that post_max_size is large enough
			ini_set( 'post_max_size', '12M' );
		}

		// Start the encoded data collection with notification command
		$encoded_data = 'cmd=_notify-validate';

		// Get current arg separator
		$arg_separator = edd_get_php_arg_separator_output();

		// Verify there is a post_data
		if ( $post_data || strlen( $post_data ) > 0 ) {

			// Append the data
			$encoded_data .= $arg_separator.$post_data;

		} else {

			// Check if POST is empty
			if ( empty( $_POST ) ) {

				// Nothing to do
				return;

			} else {

				// Loop through each POST
				foreach ( $_POST as $key => $value ) {

					// Encode the value and append the data
					$encoded_data .= $arg_separator."$key=" . urlencode( $value );

				}

			}

		}

		// Convert collected post data to an array
		parse_str( $encoded_data, $encoded_data_array );

		if ( ! edd_get_option( 'disable_paypal_verification' ) && ! edd_is_test_mode() ) {

			// Validate the IPN
			$remote_post_vars      = array(
				'method'           => 'POST',
				'timeout'          => 45,
				'redirection'      => 5,
				'httpversion'      => '1.1',
				'blocking'         => true,
				'headers'          => array(
					'host'         => 'www.paypal.com',
					'connection'   => 'close',
					'content-type' => 'application/x-www-form-urlencoded',
					'post'         => '/cgi-bin/webscr HTTP/1.1',

				),
				'body'             => $encoded_data_array
			);

			// Get response
			$api_response = wp_remote_post( edd_get_paypal_redirect(), $remote_post_vars );
			$body         = wp_remote_retrieve_body( $api_response );

			if ( is_wp_error( $api_response ) ) {
				edd_record_gateway_error( __( 'IPN Error', 'edd-recurring' ), sprintf( __( 'Invalid PayPal Express IPN verification response. IPN data: %s', 'edd-recurring' ), json_encode( $api_response ) ) );
				status_header( 401 );
				return; // Something went wrong
			}

			if ( $body !== 'VERIFIED' ) {
				status_header( 401 );
				edd_record_gateway_error( __( 'IPN Error', 'edd-recurring' ), sprintf( __( 'Invalid PayPal Express IPN verification response. IPN data: %s', 'edd-recurring' ), json_encode( $api_response ) ) );
				return; // Response not okay
			}

		}

		/**
		 * The processIpn() method returned true if the IPN was "VERIFIED" and false if it was "INVALID".
		 */
		if ( ( $verified || edd_get_option( 'disable_paypal_verification' ) ) || isset( $_POST['verification_override'] ) || edd_is_test_mode() ) {

			status_header( 200 );

			$posted          = apply_filters( 'edd_recurring_ipn_post', $_POST ); // allow $_POST to be modified
			$amount          = number_format( (float) $posted['mc_gross'], 2 );
			$payment_status  = $posted['payment_status'];
			$currency_code   = $posted['mc_currency'];
			$subscription    = new EDD_Subscription( $posted['recurring_payment_id'], true );

			if( empty( $subscription->id ) || $subscription->id < 1 )  {
				die( 'No subscription found' );
			}

			// Subscriptions
			switch ( $posted['txn_type'] ) :

				case "recurring_payment_profile_created" :

					$subscription->update( array( 'status' => 'active' ) );
					if( ! empty( $posted['initial_payment_txn_id'] ) ) {
						edd_set_payment_transaction_id( $subscription->parent_payment_id, $posted['initial_payment_txn_id'] );
					}
					die( 'subscription marked as active' );

					break;

				case "recurring_payment" :

					if ( strtolower( $currency_code ) != strtolower( edd_get_currency() ) ) {

						// the currency code is invalid
						// @TODO: Does this need a parent_id for better error organization?
						edd_record_gateway_error( __( 'Invalid Currency Code', 'edd-recurring' ), sprintf( __( 'The currency code in an IPN request did not match the site currency code. Payment data: %s', 'edd-recurring' ), json_encode( $payment_data ) ) );

						die( 'invalid currency code' );

					}

					// Bail if this is the very first payment
					if( date( 'Y-n-d', strtotime( $subscription->created ) ) == date( 'Y-n-d', strtotime( $posted['payment_date'] ) ) ) {

						edd_set_payment_transaction_id( $subscription->parent_payment_id, $posted['txn_id'] );

						return;
					}

					// when a user makes a recurring payment
					$subscription->add_payment( array(
						'amount'         => $amount,
						'transaction_id' => $posted['txn_id']
					) );
					$subscription->renew();

					die( 'Subscription payment successful' );

					break;

				case "recurring_payment_profile_cancel" :
				case "recurring_payment_suspended" :
				case "recurring_payment_suspended_due_to_max_failed_payment" :

					$subscription->cancel();

					die( 'Subscription cancelled' );

					break;

				case "recurring_payment_failed" :

					$subscription->failing();
					do_action( 'edd_recurring_payment_failed', $subscription );

					break;

				case "recurring_payment_expired" :

					$subscription->complete();

					die( 'Subscription completed' );
					break;

				default :

					die( 'Paypal Pro Endpoint' );
					break;

			endswitch;

		} else {

			status_header( 400 );
			die( 'invalid IPN' );

		}

	}

	/**
	 * Refund charges and cancel subscription when refunding via View Order Details
	 *
	 * @access      public
	 * @since       2.4.11
	 * @return      void
	 */
	public function process_refund( EDD_Payment $payment ) {

		if( empty( $_POST['edd-paypal-refund'] ) ) {
			return;
		}

		$statuses = array( 'edd_subscription', 'publish', 'revoked' );

		if ( ! in_array( $payment->old_status, $statuses ) ) {
			return;
		}

		if ( 'paypalexpress' !== $payment->gateway ) {
			return;
		}
		switch( $payment->old_status ) {

			case 'edd_subscription' :

				// Possibly add subscription cancellation here too

				break;

			case 'publish' :
			case 'revoked' :

				// Cancel all associated subscriptions

				$db   = new EDD_Subscriptions_DB;
				$subs = $db->get_subscriptions( array( 'parent_payment_id' => $payment->ID, 'number' => 100 ) );

				if( empty( $subs ) ) {

					return;

				}

				$success = false;

				$args = array(
					'USER'          => $this->username,
					'PWD'           => $this->password,
					'SIGNATURE'     => $this->signature,
					'VERSION'       => '124',
					'METHOD'        => 'RefundTransaction',
					'TRANSACTIONID' => $payment->transaction_id,
					'REFUNDTYPE'    => 'Full'
				);

				$error_msg = '';
				$request   = wp_remote_post( $this->api_endpoint, array( 'body' => $args, 'timeout' => 15, 'httpversion' => '1.1' ) );
				$body      = wp_remote_retrieve_body( $request );
				$code      = wp_remote_retrieve_response_code( $request );
				$message   = wp_remote_retrieve_response_message( $request );

				if ( is_wp_error( $request ) ) {

					$success   = false;
					$error_msg = $request->get_error_message();

				} else {

					if( is_string( $body ) ) {
						wp_parse_str( $body, $body );
					}

					if( empty( $code ) || 200 !== (int) $code ) {
						$success = false;
					}

					if( empty( $message ) || 'OK' !== $message ) {
						$success = false;
					}

					if( isset( $body['ACK'] ) && 'success' === strtolower( $body['ACK'] ) ) {
						$success = true;
					} else {
						$success = false;
						if( isset( $body['L_LONGMESSAGE0'] ) ) {
							$error_msg = $body['L_LONGMESSAGE0'];
							$payment->add_note( sprintf( __( 'PayPal Express refund failed: %s', 'edd-recurring' ), $error_msg ) );
						}
					}

				}

				if( $success ) {

					// Prevents the PayPal Express one-time gateway from trying to process the refundl
					$payment->update_meta( '_edd_paypalexpress_refunded', true );
					$payment->add_note( sprintf( __( 'PayPal Express Refund Transaction ID: %s', 'edd-recurring' ), $body['REFUNDTRANSACTIONID'] ) );

					foreach( $subs as $subscription ) {

						if ( 'cancelled' !== $subscription->status ) {
							// Cancel subscription
							$this->cancel( $subscription, true );
							$subscription->cancel();
							$payment->add_note( sprintf( __( 'Subscription %d cancelled.', 'edd-recurring' ), $subscription->id ) );
						}

					}

				}

				// End publish/revoked case
				break;

		} // End switch

	}

	/**
	 * Determines if the subscription can be cancelled
	 *
	 * @access      public
	 * @since       2.4
	 * @return      bool
	 */
	public function can_cancel( $ret, $subscription ) {
		if( $subscription->gateway === 'paypalexpress' && ! empty( $subscription->profile_id ) && 'active' === $subscription->status ) {
			return true;
		}
		return $ret;
	}

	/**
	 * Cancels a subscription
	 *
	 * @access      public
	 * @since       2.4
	 * @return      string
	 */
	public function cancel( $subscription, $valid ) {

		if( empty( $valid ) ) {
			return false;
		}

		$customer = new EDD_Recurring_Subscriber( $subscription->customer_id );

		$args = array(
			'USER'      => $this->username,
			'PWD'       => $this->password,
			'SIGNATURE' => $this->signature,
			'VERSION'   => '124',
			'METHOD'    => 'ManageRecurringPaymentsProfileStatus',
			'PROFILEID' => $subscription->profile_id,
			'ACTION'    => 'Cancel'
		);

		$error_msg = '';
		$request   = wp_remote_post( $this->api_endpoint, array( 'body' => $args, 'timeout' => 30, 'httpversion' => '1.1' ) );
		$body      = wp_remote_retrieve_body( $request );
		$code      = wp_remote_retrieve_response_code( $request );
		$message   = wp_remote_retrieve_response_message( $request );


		if ( is_wp_error( $request ) ) {

			$success   = false;
			$error_msg = $request->get_error_message();

		} else {

			if( is_string( $body ) ) {
				wp_parse_str( $body, $body );
			}

			if( empty( $code ) || 200 !== (int) $code ) {
				$success = false;
			}

			if( empty( $message ) || 'OK' !== $message ) {
				$success = false;
			}

			if( isset( $body['ACK'] ) && 'success' === strtolower( $body['ACK'] ) ) {
				$success = true;
			} else {
				$success = false;
				if( isset( $body['L_LONGMESSAGE0'] ) ) {
					$error_msg = $body['L_LONGMESSAGE0'];
				}
			}

		}

		if( empty( $success ) ) {
			wp_die( sprintf( __( 'There was a problem cancelling the subscription, please contact customer support. Error: %s', 'edd-recurring' ), $error_msg ), array( 'response' => 400 ) );
		}

		return true;

	}

	/**
	 * Retrieves subscription details (status and expiration)
	 *
	 * @access      public
	 * @since       2.4
	 * @return      array
	 */
	public function get_subscription_details( EDD_Subscription $subscription ) {

		$ret = array(
			'status'     => '',
			'expiration' => '',
			'error'      => '',
		);

		if( ! $subscription->id > 0 ) {

			$ret['error'] = new WP_Error( 'invalid_subscription', __( 'Invalid subscription object supplied', 'edd-recurring' ) );

		} else {

			if( ! empty( $subscription->profile_id ) ) {

				$args = array(
					'USER'      => $this->username,
					'PWD'       => $this->password,
					'SIGNATURE' => $this->signature,
					'VERSION'   => '124',
					'METHOD'    => 'GetRecurringPaymentsProfileDetails',
					'PROFILEID' => $subscription->profile_id,
				);

				$error_msg = '';
				$request   = wp_remote_post( $this->api_endpoint, array( 'body' => $args, 'timeout' => 30, 'httpversion' => '1.1' ) );
				$body      = wp_remote_retrieve_body( $request );
				$code      = wp_remote_retrieve_response_code( $request );
				$message   = wp_remote_retrieve_response_message( $request );

				if ( is_wp_error( $request ) ) {

					$ret['error'] = $request;

				} else {

					if( is_string( $body ) ) {
						wp_parse_str( $body, $body );
					}

					if( empty( $code ) || 200 !== (int) $code ) {
						$ret['error'] = new WP_Error( 'paypal_api_error', sprintf( __( 'Non 200 response code. Response code was: %s', 'edd-recurring' ), $code ) );
					}

					if( empty( $message ) || 'OK' !== $message ) {
						$ret['error'] = new WP_Error( 'paypal_api_error', sprintf( __( 'Response message not okay. Response message was: %s', 'edd-recurring' ), $message ) );
					}

					if( isset( $body['ACK'] ) && 'failure' === strtolower( $body['ACK'] ) ) {
						$ret['error'] = new WP_Error( 'paypal_api_error', $body['L_ERRORCODE0'] . ': '. $body['L_LONGMESSAGE0'] );
					}

					if( empty( $ret['error'] ) ) {

						// All good, let's grab the details of the subscription
						$ret['status']     = strtolower( $body['STATUS'] );
						$ret['expiration'] = date( 'Y-n-d H:i:s', strtotime( $body['NEXTBILLINGDATE'] ) );

					}

				}

			} else {

				$ret['error'] = new WP_Error( 'missing_profile_id', __( 'No profile_id set on subscription object', 'edd-recurring' ) );

			}

		}

		return $ret;
	}

	/**
	 * Link the recurring profile in PayPal.
	 *
	 * @since  2.4.4
	 * @param  string $profile_id   The recurring profile id
	 * @param  object $subscription The Subscription object
	 * @return string               The link to return or just the profile id
	 */
	public function link_profile_id( $profile_id, $subscription ) {

		if( ! empty( $profile_id ) ) {
			$html     = '<a href="%s" target="_blank">' . $profile_id . '</a>';

			$payment  = new EDD_Payment( $subscription->parent_payment_id );
			$base_url = 'live' === $payment->mode ? 'https://www.paypal.com' : 'https://www.sandbox.paypal.com';
			$link     = esc_url( $base_url . '/cgi-bin/webscr?cmd=_profile-recurring-payments&encrypted_profile_id=' . $profile_id );

			$profile_id = sprintf( $html, $link );
		}

		return $profile_id;

	}

}
$edd_recurring_paypal_express = new EDD_Recurring_PayPal_Express();
