<?php
/**
 * Plugin Name: Easy Digital Downloads - Recurring Payments
 * Plugin URI: http://easydigitaldownloads.com/downloads/edd-recurring
 * Description: Sell subscriptions with Easy Digital Downloads
 * Author: Easy Digital Downloads
 * Author URI: https://easydigitaldownloads.com
 * Version: 2.4.15
 * Text Domain: edd-recurring
 * Domain Path: languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EDD_RECURRING_STORE_API_URL', 'https://easydigitaldownloads.com' );
define( 'EDD_RECURRING_PRODUCT_NAME', 'Recurring Payments' );

if ( ! defined( 'EDD_RECURRING_PLUGIN_DIR' ) ) {
	define( 'EDD_RECURRING_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'EDD_RECURRING_PLUGIN_URL' ) ) {
	define( 'EDD_RECURRING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'EDD_RECURRING_PLUGIN_FILE' ) ) {
	define( 'EDD_RECURRING_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'EDD_RECURRING_VERSION' ) ) {
	define( 'EDD_RECURRING_VERSION', '2.4.15' );
}

final class EDD_Recurring {


	/** Singleton *************************************************************/

	/**
	 * @var EDD_Recurring The one true EDD_Recurring
	 */
	private static $instance;

	static $plugin_path;
	static $plugin_dir;

	public static $gateways = array();


	/**
	 * @var EDD_Recurring_Customer
	 */
	public static $customers;

	/**
	 * @var EDD_Recurring_Content_Restriction
	 */
	public static $content_restriction;

	/**
	 * @var EDD_Recurring_Software_Licensing
	 */
	public static $software_licensing;

	/**
	 * @var EDD_Recurring_Auto_Register
	 */
	public static $auto_register;

	/**
	 * @var EDD_Recurring_Reminders
	 */
	public static $reminders;

	/**
	 * @var EDD_Recurring_Emails
	 */
	public static $emails;

	/**
	 * @var EDD_Recurring_Cron
	 */
	public static $cron;

	/**
	 * @var EDD_Subscriptions_API
	 */
	public static $api;

	/**
	 * Main EDD_Recurring Instance
	 *
	 * Insures that only one instance of EDD_Recurring exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since     v1.0
	 * @staticvar array $instance
	 * @uses      EDD_Recurring::setup_globals() Setup the globals needed
	 * @uses      EDD_Recurring::includes() Include the required files
	 * @uses      EDD_Recurring::setup_actions() Setup the hooks and actions
	 * @see       EDD()
	 * @return EDD_Recurring The one true EDD_Recurring
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new EDD_Recurring;

			self::$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
			self::$plugin_dir  = untrailingslashit( plugin_dir_url( __FILE__ ) );

			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Constructor -- prevent new instances
	 *
	 * @since 2.4.1
	 */
	private function __construct(){
		//You shall not pass.
	}

	/**
	 * Get things started
	 *
	 * Sets up globals, loads text domain, loads includes, inits actions and filters, starts customer class
	 *
	 * @since v1.0
	 */
	function init() {

		self::includes_global();

		if ( is_admin() ) {
			self::includes_admin();
		}

		if( EDD_RECURRING_VERSION != get_option( 'edd_recurring_version' ) ){
			edd_recurring_install();
		}

		self::load_textdomain();

		self::actions();
		self::filters();

		self::$customers           = new EDD_Recurring_Customer();
		self::$content_restriction = new EDD_Recurring_Content_Restriction();
		self::$software_licensing  = new EDD_Recurring_Software_Licensing();
		self::$auto_register       = new EDD_Recurring_Auto_Register();
		self::$api                 = new EDD_Subscriptions_API();
		self::$reminders           = new EDD_Recurring_Reminders();
		self::$emails              = new EDD_Recurring_Emails();
		self::$cron                = new EDD_Recurring_Cron();

		self::$gateways = array(
			'2checkout'        => 'EDD_Recurring_2Checkout',
			'2checkout_onsite' => 'EDD_Recurring_2Checkout_Onsite',
			'authorize'        => 'EDD_Recurring_Authorize',
			'manual'           => 'EDD_Recurring_Manual_Payments',
			'paypal'           => 'EDD_Recurring_PayPal',
			'paypalexpress'    => 'EDD_Recurring_PayPal_Express',
			'paypalpro'        => 'EDD_Recurring_PayPal_Website_Payments_Pro',
			'stripe'           => 'EDD_Recurring_Stripe',
		);

	}


	/**
	 * Load global files
	 *
	 * @since  1.0
	 * @return void
	 */
	public function includes_global() {
		$files = array(
			'edd-subscriptions-db.php',
			'edd-subscription.php',
			'edd-subscriptions-api.php',
			'edd-recurring-cron.php',
			'edd-recurring-subscriber.php',
			'edd-recurring-shortcodes.php',
			'gateways/edd-recurring-gateway.php',
			'plugin-content-restriction.php',
			'edd-recurring-emails.php',
			'edd-recurring-reminders.php',
			'plugin-software-licensing.php',
			'plugin-auto-register.php',
			'deprecated/edd-recurring-customer.php'
		);

		//Load main files
		foreach ( $files as $file ) {
			require( sprintf( '%s/includes/%s', self::$plugin_path, $file ) );
		}

		//Load gateway functions
		foreach ( edd_get_payment_gateways() as $key => $gateway ) {
			if ( file_exists( EDD_RECURRING_PLUGIN_DIR . 'includes/gateways/' . $key . '/functions.php' ) ) {
				require_once EDD_RECURRING_PLUGIN_DIR . 'includes/gateways/' . $key . '/functions.php';
			}
		}

		//Load gateway classes
		foreach ( edd_get_payment_gateways() as $gateway_id => $gateway ) {
			if( file_exists( sprintf( '%s/includes/gateways/edd-recurring-%s.php', self::$plugin_path, $gateway_id ) ) ) {
				require( sprintf( '%s/includes/gateways/edd-recurring-%s.php', self::$plugin_path, $gateway_id ) );
			}
		}

	}

	/**
	 * Load admin files
	 *
	 * @since  1.0
	 * @return void
	 */
	public function includes_admin() {
		$files = array(
			'upgrade-functions.php',
			'customers.php',
			'class-admin-notices.php',
			'class-subscriptions-list-table.php',
			'class-summary-widget.php',
			'class-recurring-reports.php',
			'subscriptions.php',
			'metabox.php',
			'settings.php',
			'scripts.php'
		);

		foreach ( $files as $file ) {
			require( sprintf( '%s/includes/admin/%s', self::$plugin_path, $file ) );
		}
	}

	/**
	 * Loads the plugin language files
	 *
	 * @since  v1.0
	 * @access private
	 * @uses   dirname()
	 * @uses   plugin_basename()
	 * @uses   apply_filters()
	 * @uses   load_textdomain()
	 * @uses   get_locale()
	 * @uses   load_plugin_textdomain()
	 *
	 */
	private function load_textdomain() {

		// Set filter for plugin's languages directory
		$edd_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
		$edd_lang_dir = apply_filters( 'edd_languages_directory', $edd_lang_dir );


		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'edd-recurring' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'edd-recurring', $locale );

		// Setup paths to current locale file
		$mofile_local  = $edd_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/edd-recurring/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/edd-recurring folder
			load_textdomain( 'edd-recurring', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/edd-recurring/languages/ folder
			load_textdomain( 'edd-recurring', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'edd-recurring', false, $edd_lang_dir );
		}

	}


	/**
	 * Add our actions
	 *
	 * @since  1.0
	 * @return void
	 */
	private function actions() {

		if ( class_exists( 'EDD_License' ) && is_admin() ) {
			$recurring_license = new EDD_License( __FILE__, EDD_RECURRING_PRODUCT_NAME, EDD_RECURRING_VERSION, 'Easy Digital Downloads', 'recurring_license_key' );
		}

		add_action( 'admin_menu', array( $this, 'subscriptions_list' ), 10 );

		// Register our "canclled" post status
		add_action( 'init', array( $this, 'register_post_statuses' ) );

		// Maybe remove the Signup fee from the cart
		add_action( 'init', array( $this, 'maybe_add_remove_fees' ) );

		// Check for subscription status on file download
		add_action( 'edd_process_verified_download', array( $this, 'process_download' ), 10, 4 );

		// Tells EDD to include subscription payments in Payment History
		add_action( 'edd_pre_get_payments', array( $this, 'enable_child_payments' ), 100 );

		// Maybe show subscription terms under purchase link
		add_action( 'edd_purchase_link_end', array( $this, 'show_single_terms_notice' ), 10, 2 );
		add_action( 'edd_after_price_option', array( $this, 'show_variable_terms_notice' ), 10, 3 );
		add_action( 'edd_checkout_cart_item_title_after', array( $this, 'show_terms_on_cart_item' ), 10, 1 );

		// Maybe show signup fee under purchase link
		add_action( 'edd_purchase_link_end', array( $this, 'show_single_signup_fee_notice' ), 10, 2 );
		add_action( 'edd_after_price_option', array( $this, 'show_variable_signup_fee_notice' ), 10, 3 );

		// Register styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		// Register scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Accounts for showing the login form when auto register is enabled, and login forms aren't shown
		add_action( 'edd_purchase_form_before_register_login', array( $this, 'force_login_fields' ) );

		// Notify a user when a subscription failed to be purchased
		add_action( 'edd_payment_receipt_before', array( $this, 'display_failed_subscriptions' ), 10, 2 );
		add_action( 'edd_retry_failed_subs', array( $this, 'process_add_failed' ) );

	}

	/**
	 * Add our filters
	 *
	 * @since  1.0
	 * @return void
	 */
	private function filters() {

		// Register our new payment statuses
		add_filter( 'edd_payment_statuses', array( $this, 'register_edd_cancelled_status' ) );

		// Set the payment stati that can download files (legacy)
		add_filter( 'edd_allowed_download_stati', array( $this, 'add_allowed_payment_status' ) );
		add_filter( 'edd_is_payment_complete', array( $this, 'is_payment_complete' ), 10, 3 );

		add_filter( 'edd_file_download_has_access', array( $this, 'allow_file_access' ), 10, 3 );

		// Show the Cancelled and Subscription status links in Payment History
		add_filter( 'edd_payments_table_views', array( $this, 'payments_view' ) );

		// Modify the cart details when purchasing a subscription
		add_filter( 'edd_add_to_cart_item', array( $this, 'add_subscription_cart_details' ), 10 );

		// Include subscription payments in the calulation of earnings
		add_filter( 'edd_get_total_earnings_args', array( $this, 'earnings_query' ) );
		add_filter( 'edd_get_earnings_by_date_args', array( $this, 'earnings_query' ) );
		add_filter( 'edd_get_sales_by_date_args', array( $this, 'earnings_query' ) );
		add_filter( 'edd_stats_earnings_args', array( $this, 'earnings_query' ) );
		add_filter( 'edd_get_users_purchases_args', array( $this, 'has_purchased_query' ) );

		// Allow PDF Invoices to be downloaded for subscription payments
		add_filter( 'eddpdfi_is_invoice_link_allowed', array( $this, 'is_invoice_allowed' ), 10, 2 );

		// Allow edd_subscription to run a refund to the gateways
		add_filter( 'edd_should_process_refund', array( $this, 'maybe_process_refund' ), 10, 2 );
		add_filter( 'edd_decrease_sales_on_undo', array( $this, 'maybe_decrease_sales' ), 10, 2 );
		add_filter( 'edd_decrease_customer_purchase_count_on_refund', array( $this, 'maybe_decrease_sales' ), 10, 2 );

		// Don't count renewals towards a customer purchase count when using recount
		add_filter( 'edd_customer_recount_sholud_increase_count', array( $this, 'maybe_increase_customer_sales' ), 10, 2 );

	}

	/**
	 * Registers renewal payment post status
	 *
	 * @since  1.0
	 * @return void
	 */
	public function register_post_statuses() {
		register_post_status( 'cancelled', array(
			'label'                     => _x( 'Cancelled', 'Cancelled payment status', 'edd-recurring' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => false,
			'show_in_admin_status_list' => false,
			'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'edd-recurring' )
		) );
		register_post_status( 'edd_subscription', array(
			'label'                     => _x( 'Renewal', 'Subscription renewal payment status', 'edd-recurring' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Renewal <span class="count">(%s)</span>', 'Renewals <span class="count">(%s)</span>', 'edd-recurring' )
		) );
	}

	/**
	 * Register our Subscriptions submenu
	 *
	 * @since  2.4
	 * @return void
	 */
	public function subscriptions_list() {
		add_submenu_page(
			'edit.php?post_type=download',
			__( 'Subscriptions', 'edd-recurring' ),
			__( 'Subscriptions', 'edd' ),
			'view_shop_reports',
			'edd-subscriptions',
			'edd_subscriptions_page'
		);
	}


	/**
	 * Allow file downloads for payments with a status of cancelled
	 *
	 * @since  1.4.2
	 * @return array
	 */
	public function add_allowed_payment_status( $stati ) {
		$stati[] = 'cancelled';

		return $stati;
	}


	/**
	 * Allow file downloads for payments with a status of cancelled
	 *
	 * @since  1.4.2
	 * @return array
	 */
	public function is_payment_complete( $ret, $payment_id, $status ) {

		if ( 'cancelled' == $status ) {

			$ret = true;

		} elseif ( 'edd_subscription' == $status ) {

			$parent = get_post_field( 'post_parent', $payment_id );
			if ( edd_is_payment_complete( $parent ) ) {
				$ret = true;
			}

		}

		return $ret;
	}

	/**
	 * Allow file download access once a renewal has processed
	 *
	 * @since  2.4.6
	 * @param  bool  $has_access   If the user has access to the file
	 * @param  int   $payment_id    The payment ID associated with the download
	 * @param  array $args        Array of arguments for the file request
	 * @return bool               If the file should be delivered or not.
	 */
	public function allow_file_access( $has_access, $payment_id, $args ) {
		$payment = new EDD_Payment( $payment_id );
		if ( 'edd_subscription' === $payment->status ) {
			$has_access = true;
		}


		return $has_access;
	}


	/**
	 * Tells EDD about our new payment status
	 *
	 * @since  1.0
	 * @return array
	 */
	public function register_edd_cancelled_status( $stati ) {
		$stati['edd_subscription'] = __( 'Renewal', 'edd-recurring' );
		return $stati;
	}


	/**
	 * Displays the cancelled payments filter link
	 *
	 * @since  1.0
	 * @return array
	 */
	public function payments_view( $views ) {
		$base          = admin_url( 'edit.php?post_type=download&page=edd-payment-history' );
		$payment_count = wp_count_posts( 'edd_payment' );
		$current       = isset( $_GET['status'] ) ? $_GET['status'] : '';

		$subscription_count        = '&nbsp;<span class="count">(' . $payment_count->edd_subscription . ')</span>';
		$views['edd_subscription'] = sprintf(
			'<a href="%s"%s>%s</a>',
			esc_url( add_query_arg( 'status', 'edd_subscription', $base ) ),
			$current === 'edd_subscription' ? ' class="current"' : '',
			__( 'Renewals', 'edd-recurring' ) . $subscription_count
		);

		return $views;
	}


	/**
	 * Add or remove the signup fees
	 *
	 * @since  2.1.6
	 * @return void
	 */
	public function maybe_add_remove_fees() {
		if ( is_admin() ) {
			return;
		}

		$fee_amount    = 0;
		$has_recurring = false;
		$cart_details  = edd_get_cart_contents();

		if ( $cart_details ) {
			foreach ( $cart_details as $item ) {

				if ( isset( $item['options'] ) && isset( $item['options']['recurring'] ) && isset( $item['options']['recurring']['signup_fee'] ) ) {

					$has_recurring = true;
					$fee_amount   += $item['options']['recurring']['signup_fee'];
				}

			}
		}

		if ( $has_recurring && ( $fee_amount > 0 || $fee_amount < 0 ) ) {
			$args = array(
				'amount' => $fee_amount,
				'label'  => __( 'Signup Fee', 'edd-recurring' ),
				'id'     => 'signup_fee',
				'type'   => 'fee'
			);
			EDD()->fees->add_fee( $args );
		} else {
			EDD()->fees->remove_fee( 'signup_fee' );
		}

	}

	/**
	 * Checks if a user has permission to download a file
	 *
	 * This allows file downloads to be limited to activesubscribers
	 *
	 * @since  1.0
	 * @return void
	 */
	public function process_download( $download_id = 0, $email = '', $payment_id = 0, $args = array() ) {

		global $edd_options;

		if ( ! edd_get_option( 'recurring_download_limit', false ) ) {
			return;
		} // Downloads not restricted to subscribers

		// Allow user to download by default
		$has_access = true;

		// Check if this is a variable priced product
		$is_variable = isset( $_GET['price_id'] ) && (int) $_GET['price_id'] !== false ? true : false;

		if ( $is_variable && edd_has_variable_prices( $download_id ) ) {
			$recurring = self::is_price_recurring( $download_id, (int) $_GET['price_id'] );
		} else {
			$recurring = self::is_recurring( $download_id );
		}

		if ( ! $recurring ) {
			return;
		} // Product isn't recurring

		$customer = new EDD_Recurring_Subscriber( $email );

		// No customer found so access is denied
		if ( ! $customer->id > 0 ) {
			$has_access = false;
		}

		// Check for active subscription
		if ( $customer->id > 0 && ! $customer->has_active_product_subscription( $download_id ) ) {

			$has_access = false;

			// Check if the purchase included a bundle
			$payment = new EDD_Payment( $payment_id );

			foreach( $payment->downloads as $download ) {

				if( edd_is_bundled_product( $download['id'] ) ) {

					$bundled = edd_get_bundled_products( $download['id'] );

					if( ! in_array( $download_id, $bundled ) ) {
						continue;
					}

					if( $customer->has_active_product_subscription( $download['id'] ) ) {

						$has_access = true;

					}

				}

			}

		}

		// User doesn't have an active subscription so deny access
		if ( ! apply_filters( 'edd_recurring_download_has_access', $has_access, $customer->user_id, $download_id, $is_variable ) ) {

			wp_die(
				sprintf(
					__( 'You must have an active subscription to %s in order to download this file.', 'edd-recurring' ),
					get_the_title( $download_id )
				),
				__( 'Access Denied', 'edd-recurring' )
			);
		}

	}


	/**
	 * Adds recurring product details to the shopping cart
	 *
	 * This fires when items are added to the cart
	 *
	 * @since  1.0
	 * @return array
	 */

	static function add_subscription_cart_details( $cart_item ) {

		$download_id = $cart_item['id'];
		$price_id    = isset( $cart_item['options']['price_id'] ) ? intval( $cart_item['options']['price_id'] ) : null;

		if ( edd_has_variable_prices( $download_id ) && ( ! empty( $price_id ) || 0 === (int) $price_id ) ) {

			// add the recurring info for a variable price
			if ( self::is_price_recurring( $download_id, $price_id ) ) {

				$cart_item['options']['recurring'] = array(
					'period'     => self::get_period( $price_id, $download_id ),
					'times'      => self::get_times( $price_id, $download_id ),
					'signup_fee' => self::get_signup_fee( $price_id, $download_id ),
				);

			}

		} else {

			// add the recurring info for a normal priced item
			if ( self::is_recurring( $download_id ) ) {

				$cart_item['options']['recurring'] = array(
					'period'     => self::get_period_single( $download_id ),
					'times'      => self::get_times_single( $download_id ),
					'signup_fee' => self::get_signup_fee_single( $download_id ),
				);

			}

		}

		return $cart_item;

	}


	/**
	 * Set up the time period IDs and labels
	 *
	 * @since  1.0
	 * @return array
	 */

	static function periods() {
		$periods = array(
			'day'   => _x( 'Daily', 'Billing period', 'edd-recurring' ),
			'week'  => _x( 'Weekly', 'Billing period', 'edd-recurring' ),
			'month' => _x( 'Monthly', 'Billing period', 'edd-recurring' ),
			'year'  => _x( 'Yearly', 'Billing period', 'edd-recurring' ),
		);

		$periods = apply_filters( 'edd_recurring_periods', $periods );

		return $periods;
	}


	/**
	 * Get the time period for a variable priced product
	 *
	 * @since  1.0
	 * @return string
	 */

	static function get_period( $price_id, $post_id = null ) {
		global $post;

		$period = 'never';

		if ( ! $post_id && is_object( $post ) ) {
			$post_id = $post->ID;
		}

		$prices = get_post_meta( $post_id, 'edd_variable_prices', true );

		if ( isset( $prices[ $price_id ]['period'] ) ) {
			$period = $prices[ $price_id ]['period'];
		}

		return $period;
	}


	/**
	 * Get the time period for a single-price product
	 *
	 * @since  1.0
	 * @return string
	 */

	static function get_period_single( $post_id ) {
		global $post;

		$period = get_post_meta( $post_id, 'edd_period', true );

		if ( $period ) {
			return $period;
		}

		return 'never';
	}


	/**
	 * Get the number of times a price ID recurs
	 *
	 * @since  1.0
	 * @return int
	 */

	static function get_times( $price_id, $post_id = null ) {
		global $post;

		if ( empty( $post_id ) && is_object( $post ) ) {
			$post_id = $post->ID;
		}

		$prices = get_post_meta( $post_id, 'edd_variable_prices', true );

		if ( isset( $prices[ $price_id ]['times'] ) ) {
			return intval( $prices[ $price_id ]['times'] );
		}

		return 0;
	}

	/**
	 * Get the signup fee a price ID
	 *
	 * @since  1.1
	 * @return float
	 */

	static function get_signup_fee( $price_id, $post_id = null ) {
		global $post;

		if ( empty( $post_id ) && is_object( $post ) ) {
			$post_id = $post->ID;
		}

		$prices = get_post_meta( $post_id, 'edd_variable_prices', true );

		$fee = isset( $prices[ $price_id ]['signup_fee'] ) ? $prices[ $price_id ]['signup_fee'] : 0;
		$fee = apply_filters( 'edd_recurring_signup_fee', $fee, $price_id, $prices );
		if ( $fee ) {
			return floatval( $fee );
		}

		return 0;
	}


	/**
	 * Get the number of times a single-price product recurs
	 *
	 * @since  1.0
	 * @return int
	 */

	static function get_times_single( $post_id ) {
		global $post;

		$times = get_post_meta( $post_id, 'edd_times', true );

		if ( $times ) {
			return $times;
		}

		return 0;
	}


	/**
	 * Get the signup fee of a single-price product
	 *
	 * @since  1.1
	 * @return float
	 */

	static function get_signup_fee_single( $post_id ) {
		global $post;

		$signup_fee = get_post_meta( $post_id, 'edd_signup_fee', true );

		if ( $signup_fee ) {
			return $signup_fee;
		}

		return 0;
	}


	/**
	 * Check if a price is recurring
	 *
	 * @since  1.0
	 * @return bool
	 */

	static function is_price_recurring( $download_id = 0, $price_id ) {

		global $post;

		if ( empty( $download_id ) && is_object( $post ) ) {
			$download_id = $post->ID;
		}

		$prices = get_post_meta( $download_id, 'edd_variable_prices', true );
		$period = self::get_period( $price_id, $download_id );

		if ( isset( $prices[ $price_id ]['recurring'] ) && 'never' != $period ) {
			return true;
		}

		return false;

	}


	/**
	 * Check if a product is recurring
	 *
	 * @since  1.0
	 *
	 * @param int $download_id
	 *
	 * @return bool
	 */
	public static function is_recurring( $download_id = 0 ) {

		global $post;

		if ( empty( $download_id ) && is_object( $post ) ) {
			$download_id = $post->ID;
		}

		if ( get_post_meta( $download_id, 'edd_recurring', true ) == 'yes' ) {
			return true;
		}

		return false;

	}

	/**
	 * Record a subscription payment
	 *
	 * @deprecated 2.4
	 * @since  1.0.1
	 * @return void
	 */
	public function record_subscription_payment( $parent_id = 0, $amount = '', $txn_id = '', $unique_key = 0 ) {

		global $edd_options;

		_edd_deprecated_function( __FUNCTION__, '2.5', 'EDD_Recurring_Subscription::add_payment()', debug_backtrace() );

		if ( $this->payment_exists( $unique_key ) ) {
			return;
		}

		// increase the earnings for each product in the subscription
		$downloads = edd_get_payment_meta_downloads( $parent_id );
		if ( $downloads ) {
			foreach ( $downloads as $download ) {
				edd_increase_earnings( $download['id'], $amount );
			}
		}

		// setup the payment daya
		$payment_data = array(
			'parent'       => $parent_id,
			'price'        => $amount,
			'user_email'   => edd_get_payment_user_email( $parent_id ),
			'purchase_key' => get_post_meta( $parent_id, '_edd_payment_purchase_key', true ),
			'currency'     => edd_get_option( 'currency', 'usd' ),
			'downloads'    => $downloads,
			'user_info'    => edd_get_payment_meta_user_info( $parent_id ),
			'cart_details' => edd_get_payment_meta_cart_details( $parent_id ),
			'status'       => 'edd_subscription',
			'gateway'      => edd_get_payment_gateway( $parent_id )
		);

		// record the subscription payment
		$payment = edd_insert_payment( $payment_data );

		if ( ! empty( $unique_key ) ) {
			update_post_meta( $payment, '_edd_recurring_' . $unique_key, '1' );
		}

		// Record transaction ID
		if ( ! empty( $txn_id ) ) {

			if ( function_exists( 'edd_set_payment_transaction_id' ) ) {
				edd_set_payment_transaction_id( $payment, $txn_id );
			}
		}

		// Update the expiration date of license keys, if EDD Software Licensing is active
		if ( function_exists( 'edd_software_licensing' ) ) {
			$licenses = edd_software_licensing()->get_licenses_of_purchase( $parent_id );

			if ( ! empty( $licenses ) ) {
				foreach ( $licenses as $license ) {
					// Update the expiration dates of the license key

					edd_software_licensing()->renew_license( $license->ID, $parent_id );

				}
			}
		}

		do_action( 'edd_recurring_record_payment', $payment, $parent_id, $amount, $txn_id, $unique_key );

	}

	/**
	 * Checks if a payment already exists
	 *
	 * @deprecated 2.4
	 * @since  1.0.2
	 * @return bool
	 */
	public function payment_exists( $unique_key = 0 ) {
		global $wpdb;

		_edd_deprecated_function( __FUNCTION__, '2.5', null, debug_backtrace() );

		if ( empty( $unique_key ) ) {
			return false;
		}

		$unique_key = esc_sql( $unique_key );

		$purchase = $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_edd_recurring_{$unique_key}' LIMIT 1" );

		if ( $purchase != null ) {
			return true;
		}

		return false;
	}


	/**
	 * Determines if a purchase contains a recurring product
	 *
	 * @since  1.0.1
	 * @return bool
	 */
	public function is_purchase_recurring( $purchase_data ) {

		if( ! empty( $purchase_data['downloads'] ) && is_array( $purchase_data['downloads'] ) ) {

			foreach ( $purchase_data['downloads'] as $download ) {

				if ( isset( $download['options'] ) && isset( $download['options']['recurring'] ) ) {
					return true;
				}
			}

		}

		return false;

	}

	/**
	 * Looks at the cart to determine if there is a recurring subscription in the cart
	 *
	 * @since   2.4
	 * @return  bool
	 */
	public function cart_contains_recurring() {

		$contains_recurring = false;

		$cart_contents = edd_get_cart_contents();
		foreach ( $cart_contents as $cart_item ) {

			if ( isset( $cart_item['options'] ) && isset( $cart_item['options']['recurring'] ) ) {

				$contains_recurring = true;
				break;

			}

		}

		return $contains_recurring;
	}

	/**
	 * Looks at the cart to determine if there are recurring and non-recurring items
	 *
	 * @since   2.4.13
	 * @return  bool
	 */
	public function cart_is_mixed() {

		$has_recurring     = false;
		$has_non_recurring = false;

		$cart_contents = edd_get_cart_contents();
		foreach ( $cart_contents as $cart_item ) {

			if ( isset( $cart_item['options'] ) && isset( $cart_item['options']['recurring'] ) ) {

				$has_recurring = true;

			} else {

				$has_non_recurring = true;

			}

		}

		return $has_recurring && $has_non_recurring;
	}


	/**
	 * Make sure subscription payments get included in earning reports
	 *
	 * @since  1.0
	 * @return array
	 */
	public function earnings_query( $args ) {
		$args['post_status'] = array( 'publish', 'revoked', 'cancelled', 'edd_subscription' );

		return $args;
	}


	/**
	 * Make sure subscription payments get included in has user purchased query
	 *
	 * @since  2.1.5
	 * @return array
	 */
	public function has_purchased_query( $args ) {
		$args['status'] = array( 'publish', 'revoked', 'cancelled', 'edd_subscription' );

		return $args;
	}


	/**
	 * Tells EDD to include child payments in queries
	 *
	 * @since  2.2
	 * @return void
	 */
	public function enable_child_payments( $query ) {
		$query->__set( 'post_parent', null );
	}

	/**
	 * Display the signup fee notice under the purchase link
	 *
	 * @since  2.4
	 * @param  int   $download_id The download ID beign displayed
	 * @param  array $args      Array of arguements for the purcahse link
	 * @return void
	 */
	public function show_single_signup_fee_notice( $download_id, $args ) {
		if ( ! self::is_recurring( $download_id ) ) {
			return;
		}

		$show_notice = edd_get_option( 'recurring_show_signup_fee_notice', false );
		if ( false === $show_notice ) {
			return;
		}

		$download = new EDD_Download( $download_id );

		if ( $download->has_variable_prices() ) {

			$prices = $download->get_prices();
			$variable_signup_fees = array();
			foreach ( $prices as $price_id => $price ) {
				$variable_signup_fees[ $price_id ] = self::get_signup_fee( $price_id, $download_id );
			}

			$high_fee = max( $variable_signup_fees );
			$low_fee  = min( $variable_signup_fees );

			// Only show the base notice if there is one signup fee, otherwise show on each variable price
			if ( $high_fee !== $low_fee ) {
				return;
			}

			$signup_fee = $low_fee;

		} else {

			$signup_fee = self::get_signup_fee_single( $download_id );

		}

		if ( empty( $signup_fee) ) {
			return;
		}

		ob_start();
		$formatted_price = edd_currency_filter( edd_format_amount( $signup_fee, edd_currency_decimal_filter() ) );
		?>
		<p class="eddr-notice eddr-signup-fee-notice">
			<em><?php printf( __( 'With %s signup fee', 'edd-recurring' ), $formatted_price ); ?></em>
		</p>
		<?php

		echo apply_filters( 'edd_recurring_single_signup_notice', ob_get_clean(), $download, $args );
	}

	/**
	 * Show the signup fees by vraible prices
	 *
	 * @since  2.4
	 * @param  int    $price_id    The price ID key
	 * @param  string $price       The Price
	 * @param  int    $download_id The download ID
	 * @return void
	 */
	public function show_variable_signup_fee_notice( $price_id, $price, $download_id ) {
		if ( ! self::is_price_recurring( $download_id, $price_id ) ) {
			return;
		}

		$show_notice = edd_get_option( 'recurring_show_signup_fee_notice', false );
		if ( false === $show_notice ) {
			return;
		}

		$signup_fee = self::get_signup_fee( $price_id, $download_id );
		if ( empty( $signup_fee ) ) {
			return;
		}

		ob_start();
		$formatted_price = edd_currency_filter( edd_format_amount( $signup_fee, edd_currency_decimal_filter() ) );
		?>
		<p class="eddr-notice eddr-signup-fee-notice variable-prices">
			<em><?php printf( __( 'With %s signup fee', 'edd-recurring' ), $formatted_price ); ?></em>
		</p>
		<?php

		echo apply_filters( 'edd_recurring_multi_signup_notice', ob_get_clean(), $download_id, $price_id );
	}

	/**
	 * Display the signup fee notice under the purchase link
	 *
	 * @since  2.4
	 * @param  int   $download_id The download ID beign displayed
	 * @param  array $args      Array of arguements for the purcahse link
	 * @return void
	 */
	public function show_single_terms_notice( $download_id, $args ) {
		if ( ! self::is_recurring( $download_id ) ) {
			return;
		}

		$show_notice = edd_get_option( 'recurring_show_terms_notice', false );
		if ( false === $show_notice ) {
			return;
		}

		if ( edd_has_variable_prices( $download_id ) ) {
			return;
		}

		$period   = self::get_period_single( $download_id );
		$times    = self::get_times_single( $download_id );

		ob_start();
		?>
		<p class="eddr-notice eddr-terms-notice">
			<em>
				<?php if ( empty( $times ) ) : ?>
					<?php printf( __( 'Billed %s until cancelled', 'edd-recurring' ), strtolower( self::get_pretty_subscription_frequency( $period ) ) ); ?>
				<?php else: ?>
					<?php printf( __( 'Billed once per %s, %d times', 'edd-recurring' ), $period, $times ); ?>
				<?php endif; ?>
			</em>
		</p>
		<?php

		echo apply_filters( 'edd_recurring_single_terms_notice', ob_get_clean(), $download_id, $args );
	}

	/**
	 * Show the signup fees by vraible prices
	 *
	 * @since  2.4
	 * @param  int    $price_id    The price ID key
	 * @param  string $price       The Price
	 * @param  int    $download_id The download ID
	 * @return void
	 */
	public function show_variable_terms_notice( $price_id, $price, $download_id ) {
		if ( ! self::is_price_recurring( $download_id, $price_id ) ) {
			return;
		}

		$show_notice = edd_get_option( 'recurring_show_terms_notice', false );
		if ( false === $show_notice ) {
			return;
		}

		$period   = self::get_period( $price_id, $download_id );
		$times    = self::get_times( $price_id, $download_id );

		ob_start();
		?>
		<p class="eddr-notice eddr-terms-notice variable-prices">
			<em>
				<?php if ( empty( $times ) ) : ?>
					<?php printf( __( 'Billed %s until cancelled', 'edd-recurring' ), strtolower( self::get_pretty_subscription_frequency( $period ) ) ); ?>
				<?php else: ?>
					<?php printf( __( 'Billed once per %s, %d times', 'edd-recurring' ), $period, $times ); ?>
				<?php endif; ?>
			</em>
		</p>
		<?php

		echo apply_filters( 'edd_recurring_multi_terms_notice', ob_get_clean(), $download_id, $price_id );
	}

	/**
	 * Disclose the subscription terms on the cart item
	 *
	 * @since  2.4
	 * @param  array $item The cart item
	 * @return void
	 */
	public function show_terms_on_cart_item( $item ) {

		$show_terms_on_checkout = apply_filters( 'edd_recurring_show_terms_on_cart_item', true, $item );

		if ( false === $show_terms_on_checkout ) {
			return;
		}

		$download_id = absint( $item['id'] );
		$download    = new EDD_Download( $download_id );

		if ( $download->has_variable_prices() ) {

			$price_id = $item['options']['price_id'];

			if ( ! self:: is_price_recurring( $download->ID, $price_id ) ) {
				return;
			}

			$period   = self::get_period( $price_id, $download->ID );
			$times    = self::get_times( $price_id, $download->ID );

		} else {

			if ( ! self::is_recurring( $download->ID ) ) {
				return;
			}

			$period = self::get_period_single( $download->ID );
			$times  = self::get_times_single( $download->ID );

		}

		ob_start();
		?>
		<p class="eddr-notice eddr-cart-item-notice">
			<em>
				<?php if ( empty( $times ) ) : ?>
					<?php printf( __( 'Billed %s until cancelled', 'edd-recurring' ), strtolower( self::get_pretty_subscription_frequency( $period ) ) ); ?>
				<?php else: ?>
					<?php printf( __( 'Billed once per %s, %d times', 'edd-recurring' ), $period, $times ); ?>
				<?php endif; ?>
			</em>
		</p>
		<?php

		echo apply_filters( 'edd_recurring_cart_item_notice', ob_get_clean(), $item );

	}

	/**
	 * Load frontend CSS files
	 *
	 * @since  2.4
	 * @return bool
	 */
	public function enqueue_styles() {
		wp_register_style( 'edd-recurring', EDD_RECURRING_PLUGIN_URL . 'assets/css/styles.css', array(), EDD_RECURRING_VERSION );
		wp_enqueue_style( 'edd-recurring' );
	}

	/**
	 * Load frontend javascript files
	 *
	 * @since  2.4
	 * @return bool
	 */
	public function enqueue_scripts() {

		global $post;

		if( empty( $post ) || ! has_shortcode( $post->post_content, 'edd_subscriptions' ) ) {
			return;
		}

		wp_register_script( 'edd-frontend-recurring', EDD_RECURRING_PLUGIN_URL . 'assets/js/edd-frontend-recurring.js', array( 'jquery' ), EDD_RECURRING_VERSION );
		wp_enqueue_script( 'edd-frontend-recurring' );
		wp_localize_script( 'edd-frontend-recurring', 'edd_recurring_vars', array(
			'confirm_cancel' => __( 'Are you sure you want to cancel your subscription?', 'edd-recurring' ),
		) );

		if( is_ssl() ) {
			wp_enqueue_style( 'dashicons' );
		}
	}

	/**
	 * Instruct EDD PDF Invoices that subscription paymentsare eligible for Invoices
	 *
	 * @since  2.2
	 * @return bool
	 */
	public function is_invoice_allowed( $ret, $payment_id ) {

		$payment_status = get_post_status( $payment_id );

		if ( 'edd_subscription' == $payment_status ) {

			$parent = get_post_field( 'post_parent', $payment_id );
			if ( edd_is_payment_complete( $parent ) ) {
				$ret = true;
			}

		}

		return $ret;
	}

	/**
	 * Checks the payment status during the refund process and allows it to be processed through the gateway
	 * if it's an edd_subscription
	 *
	 * @since  2.4
	 * @param  bool   $process_refund The current status of if a refund should be processed
	 * @param  object $payment        The EDD_Payment object of the refund being processed
	 * @return bool                   If the payment should be procssed as a refund
	 */
	public function maybe_process_refund( $process_refund, $payment ) {

		if ( 'edd_subscription' === $payment->old_status ) {
			$process_refund = true;
		}

		return $process_refund;

	}

	/**
	 * Checks the payment status during the refund process and tells EDD to not decrease sales
	 * if it's an edd_subscription
	 *
	 * @since  2.4
	 * @param  bool   $decrease_sales The current status of if sales counts should be decreased
	 * @param  object $payment        The EDD_Payment object of the refund being processed
	 * @return bool                   If the sales counts should be decreased
	 */
	public function maybe_decrease_sales( $decrease_sales, $payment ) {

		if ( ! empty( $payment->parent_payment ) && 'refunded' === $payment->status ) {
			$decrease_sales = false;
		}

		return $decrease_sales;

	}

	/**
	 * Checks if the payment being added to a customer via recount should increase the purchase_count
	 *
	 * @since  2.4.5
	 * @param  bool   $increase_sales The current status of if we should increase sales.
	 * @param  object $payment        The WP_Post object of the payment.
	 * @return bool                   If we should increase the customer sales count.
	 */
	public function maybe_increase_customer_sales( $increase_sales, $payment ) {

		if ( 'edd_subscription' === $payment->post_status ) {
			$increase_sales = false;
		}

		return $increase_sales;

	}

	/**
	 * Get User ID from customer recurring ID
	 *
	 * @since  2.4
	 * @return int
	 */
	public function get_user_id_by_recurring_customer_id( $recurring_id = '' ) {

		global $wpdb;

		$user_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '_edd_recurring_id' AND meta_value = %s LIMIT 1", $recurring_id ) );

		if ( $user_id != NULL ) {
			return $user_id;
		}

		return 0;

	}


	/**
	 * Get pretty subscription frequency
	 *
	 * @param $period
	 *
	 * @return mixed|string|void
	 */
	public function get_pretty_subscription_frequency( $period ) {
		$frequency = '';
		//Format period details
		switch ( $period ) {
			case 'day' :
				$frequency = __( 'Daily', 'edd-recurring' );
				break;
			case 'week' :
				$frequency = __( 'Weekly', 'edd-recurring' );
				break;
			case 'month' :
				$frequency = __( 'Monthly', 'edd-recurring' );
				break;
			case 'year' :
				$frequency = __( 'Yearly', 'edd-recurring' );
				break;
			default :
				$frequency = apply_filters( 'edd_recurring_subscription_frequency', $frequency, $period );
				break;
		}

		return $frequency;

	}

	/**
	 * Get gateway class
	 *
	 * @param $gateway
	 *
	 * @return string Gateway class name
	 */
	public function get_gateway_class( $gateway = '' ) {

		$class = false;

		if( isset( self::$gateways[ $gateway ] ) ) {
			$class = self::$gateways[ $gateway ];
		}

		return $class;

	}

	/**
	 * If a purchase fails b/c of not being logged in, show the login form if it doesn't show
	 * Covers a use case of auto-register being enabled, and a user account already existing for the email
	 * address used
	 *
	 * @since  2.4.8
	 * @return void
	 */
	public function force_login_fields() {
		if ( isset( $_GET['edd-recurring-login'] ) && '1' === $_GET['edd-recurring-login'] ) {
			?>
			<div class="edd-alert edd-alert-info">
				<p><?php _e( 'An account was detected for your email. Please log in to continue your purchase.', 'edd-recurring' ); ?></p>
				<p>
					<a href="<?php echo wp_lostpassword_url(); ?>" title="<?php _e( 'Lost Password', 'edd-recurring' ); ?>">
						<?php _e( 'Lost Password?', 'edd-recurring' ); ?>
					</a>
				</p>
			</div>
			<?php
			$show_register_form = edd_get_option( 'show_register_form', 'none' ) ;

			if ( 'both' === $show_register_form || 'login' === $show_register_form ) {
				return;
			}
			do_action( 'edd_purchase_form_login_fields' );
		}
	}

	/**
	 * If multiple subscriptions are in the cart and one fails, notifiy the customer about it but process the rest
	 *
	 * @since  2.4.14
	 * @param  WP_Post $payment      The WP_Post object of the payment
	 * @param  array   $receipt_args Array of arguments of the payment receipt
	 * @return void
	 */
	public function display_failed_subscriptions( $payment, $receipt_args ) {
		$payment = new EDD_Payment( $payment->ID );
		$failed_subscriptions = $payment->get_meta( '_edd_recurring_failed_subscriptions', true );

		if ( empty( $failed_subscriptions ) ) {
			return;
		}
		$subscription_names = wp_list_pluck( $failed_subscriptions, 'subscription' );
		$subscription_names = implode( ', ', wp_list_pluck( $subscription_names, 'name' ) );

		$error_messages = array();
		$link_data      = array( 'download_ids' => array(), 'price_ids' => array() );

		foreach ( $failed_subscriptions as $key => $subscription ) {
			$error_hash = md5( $subscription['error'] );

			if ( ! isset( $error_messages[ $error_hash ] ) ) {
				$error_messages[ $error_hash ]['message']       = $subscription['error'];
				$error_messages[ $error_hash ]['subscriptions'] = array();

			}

			$error_messages[ $error_hash ]['subscriptions'][] = $subscription;

			$link_data['download_ids'][] = $subscription['subscription']['id'];
			$link_data['price_ids'][]    = ! empty( $subscription['subscription']['price_id'] ) ? $subscription['subscription']['price_id'] : 0;
		}
		?>
		<div class="eddr-failed-subscription-notice">
			<div class="edd-alert edd-alert-warn">
				<p>
					<strong><?php _e( 'Notice', 'edd-recurring' ); ?>:</strong> <?php _e( 'Your purchase is completed, but we encountered an issue while processing payments for the following items', 'edd-recurring' ); ?>:
				</p>
				<p class="edd-recurring-failed-list">
					<?php foreach ( $failed_subscriptions as $key => $subscription ) : ?>
						<span>&mdash;&nbsp;<strong><?php echo $subscription['subscription']['name']; ?></strong>: <?php echo $subscription['error']; ?></span>
					<?php endforeach; ?>
				</p>
				<p>
					<?php _e( 'The above items were removed from the purchase and you were not charged for them. You can attempt to repurchase them at your convenience. All other items were purchased successfully.', 'edd-recurring' ); ?>
				</p>
				<p>
					<form id="edd-recurring-add-failed" class="edd-form" method="post">
						<?php foreach ( $failed_subscriptions as $key => $subscription ) : ?>
							<input type="hidden" name="failed-subs[<?php echo $key; ?>][id]" value="<?php echo $subscription['subscription']['id']; ?>" />
							<?php if ( is_numeric( $subscription['subscription']['price_id'] ) ) : ?>
								<input type="hidden" name="failed-subs[<?php echo $key; ?>][price_id]" value="<?php echo $subscription['subscription']['price_id']; ?>" />
							<?php endif; ?>
						<?php endforeach; ?>
						<input type="submit" class="button" name="edd_recurring_add_failed" value="<?php _e( 'Try Again', 'edd-recurring' ); ?>"/>
						<input type="hidden" name="edd_action" value="retry_failed_subs"/>
						<?php wp_nonce_field( 'edd_retry_failed_subs_nonce', 'edd_retry_failed_subs' ); ?>
					</form>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Listen for the action to add failed subscriptions to the cart again
	 *
	 * @since  2.4.14
	 * @return void
	 */
	public function process_add_failed() {
		if( empty( $_POST['edd_recurring_add_failed'] ) ) {
			return;
		}
		if( ! is_user_logged_in() ) {
			return;
		}
		if( ! wp_verify_nonce( $_POST['edd_retry_failed_subs'], 'edd_retry_failed_subs_nonce' ) ) {
			wp_die( __( 'Error', 'edd-recurring' ), __( 'Nonce verification failed', 'edd-recurring' ), array( 'response' => 403 ) );
		}

		$failed_subs = $_POST['failed-subs'];
		if ( ! is_array( $failed_subs ) ) {
			return;
		}

		foreach ( $failed_subs as $key => $sub ) {
			$options = array();

			if ( isset( $sub['price_id'] ) && is_numeric( $sub['price_id'] ) ) {
				$options['price_id'] = $sub['price_id'];
			}

			edd_add_to_cart( absint( $sub['id'] ), $options );
		}

		wp_redirect( edd_get_checkout_uri() ); exit;
	}

}

/**
 * The main function responsible for returning the one true EDD_Recurring Instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $recurring = EDD_Recurring(); ?>
 *
 * @since v1.0
 *
 * @return EDD_Recurring The one true EDD_Recurring Instance
 */
function EDD_Recurring() {

	if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
		return;
	}

	return EDD_Recurring::instance();
}
add_action( 'plugins_loaded', 'EDD_Recurring', 100 );


/**
 * Install hook
 *
 * @since v2.4
 */
function edd_recurring_install() {

	global $wpdb;

	EDD_Recurring();

	if ( class_exists( 'EDD_Subscriptions_DB' ) ) {

		$db = new EDD_Subscriptions_DB;
		@$db->create_table();

		add_role( 'edd_subscriber', __( 'EDD Subscriber', 'edd-recurring' ), array( 'read' ) );

		$version = get_option( 'edd_recurring_version' );

		if( empty( $version ) ) {

			// This is a new install or an update from pre 2.4, look to see if we have recurring products
			$has_recurring = $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'edd_period' OR ( meta_key = 'edd_variable_prices' AND meta_value LIKE '%recurring%' AND meta_value LIKE '%yes%' ) AND 1=1 LIMIT 1" );
			$needs_upgrade = ! empty( $has_recurring );

			if( ! $needs_upgrade ) {
				// Make sure this upgrade routine is never shown as needed
				edd_set_upgrade_complete( 'upgrade_24_subscriptions' );
			}

		}

		if( ! is_admin() ) {

			// Make sure our admin files with edd_recurring_needs_24_stripe_fix() definition are loaded
			EDD_Recurring()->includes_admin();
			require_once EDD_PLUGIN_DIR . 'includes/admin/upgrades/upgrade-functions.php';

		}

		if ( false === edd_recurring_needs_24_stripe_fix() ) {
			edd_set_upgrade_complete( 'fix_24_stripe_customers' );
		}

		update_option( 'edd_recurring_version', EDD_RECURRING_VERSION );

	}

}
register_activation_hook( __FILE__, 'edd_recurring_install' );
