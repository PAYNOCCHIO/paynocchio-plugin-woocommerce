<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cfps.co
 * @since      1.0.0
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/includes
 * @author     CFP TECHNOLOGY <info@cfps.co>
 */
class Woocommerce_Paynocchio {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_Paynocchio_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

    /**
     * Is current user logged in?
     *
     * @since    1.0.0
     * @access   protected
     * @var      boolean    $is_user_logged_in    Check current user.
     */
    private  $is_user_logged_in;

    /**
     * Current User id
     *
     * @since    1.0.0
     * @access   protected
     * @var      $user_id
     */

    public $user_id;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WOOCOMMERCE_PAYNOCCHIO_VERSION' ) ) {
			$this->version = WOOCOMMERCE_PAYNOCCHIO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-paynocchio';

		$this->init();
		$this->load_dependencies();
		$this->set_locale();
		$this->add_gateway();
		$this->add_routes();
		$this->add_shortcodes();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function init()
    {
        add_action('init', function(){
            $this->is_user_logged_in = is_user_logged_in();
            if($this->is_user_logged_in) {
                $this->user_id = $this->get_user_id();
            }
        });

    }

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Woocommerce_Paynocchio_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Paynocchio_i18n. Defines internationalization functionality.
	 * - Woocommerce_Paynocchio_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Paynocchio_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-paynocchio-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-paynocchio-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-paynocchio-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-paynocchio-admin-users-export-menu.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-paynocchio-admin-users-export.php';

		/**
		 * The class responsible for defining Paynocchio Wocommerce payment Gateway.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-add-gateway.php';

		/**
		 * The class responsible for defining Paynocchio Wocommerce custom RESTapi Routes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-add-restapi-routes.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-paynocchio-public.php';

		/**
		 * Shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-paynocchio-shortcodes.php';

		/**
		 * Paynocchio Wallet Class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-paynocchio-wallet.php';

		$this->loader = new Woocommerce_Paynocchio_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_Paynocchio_Add_Gateway class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_gateway() {

	    $add_gateway = new Woocommerce_Paynocchio_Add_Gateway();

		$this->loader->add_action( 'plugins_loaded', $add_gateway, 'add_woocommerce_gateway' );

	}
	/**
	 * Add custom REST API routes
	 *
	 * Uses the Woocommerce_Paynocchio_Add_RESTapi_routes class in order to set the routes and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_routes() {

	    $add_routes = new Woocommerce_Paynocchio_Add_RESTapi_Routes();

		$this->loader->add_action( 'rest_api_init', $add_routes, 'add_custom_routes' );

	}
	/**
	 * Add shortcodes
	 *
	 * Uses the Woocommerce_Paynocchio_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_shortcodes() {

        add_shortcode( 'paynocchio_activation_block', [ 'Woocommerce_Paynocchio_Shortcodes', 'paynocchio_activation_block' ] );
        add_shortcode( 'paynocchio_registration_block', [ 'Woocommerce_Paynocchio_Shortcodes', 'paynocchio_registration_block' ] );
        add_shortcode( 'paynocchio_account_page', [ 'Woocommerce_Paynocchio_Shortcodes', 'paynocchio_account_page' ] );
        add_shortcode( 'paynocchio_cart_wallet_widget', [ 'Woocommerce_Paynocchio_Shortcodes', 'paynocchio_cart_wallet_widget' ] );
        add_shortcode( 'paynocchio_kopybara_cart_wallet_widget', [ 'Woocommerce_Paynocchio_Shortcodes', 'paynocchio_kopybara_cart_wallet_widget' ] );
        add_shortcode( 'paynocchio_payment_widget', [ 'Woocommerce_Paynocchio_Shortcodes', 'paynocchio_payment_widget' ] );
        add_shortcode( 'paynocchio_modal_forms', [ 'Woocommerce_Paynocchio_Shortcodes', 'paynocchio_modal_forms' ] );

	}

	/**
	 * Register Woocommerce custom gateway
	 *
	 * Uses the WC_Payment_Gateway.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_Paynocchio_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woocommerce_Paynocchio_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// TODO: add Block editor Support
		//$this->loader->add_action( 'woocommerce_blocks_loaded', $plugin_admin, 'paynocchio_gateway_block_support' );

        add_action( 'plugins_loaded', function() {
            $plugin_user_export = new Paynocchio_Users_Export_Menu( new Paynocchio_Users_Export_Page() );
            $plugin_user_export->init();
        } );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_Paynocchio_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_login', [$this, 'paynocchio_ajax_login']);
        add_action( 'wp_ajax_paynocchio_ajax_activation', [$this, 'paynocchio_ajax_activation']);
        add_action( 'wp_ajax_paynocchio_ajax_registration', [$this, 'paynocchio_ajax_registration']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_registration', [$this, 'paynocchio_ajax_registration']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_activation', [$this, 'paynocchio_ajax_activation']);
        add_action( 'wp_ajax_paynocchio_ajax_top_up', [$this, 'paynocchio_ajax_top_up']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_top_up', [$this, 'paynocchio_ajax_top_up']);
        add_action( 'wp_ajax_paynocchio_ajax_set_status', [$this, 'paynocchio_ajax_set_status']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_set_status', [$this, 'paynocchio_ajax_set_status']);
        add_action( 'wp_ajax_paynocchio_ajax_delete_wallet', [$this, 'paynocchio_ajax_delete_wallet']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_delete_wallet', [$this, 'paynocchio_ajax_delete_wallet']);
        add_action( 'wp_ajax_paynocchio_ajax_check_balance', [$this, 'paynocchio_ajax_check_balance']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_check_balance', [$this, 'paynocchio_ajax_check_balance']);
        add_action( 'wp_ajax_paynocchio_ajax_get_user_wallet', [$this, 'paynocchio_ajax_get_user_wallet']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_get_user_wallet', [$this, 'paynocchio_ajax_get_user_wallet']);
        add_action( 'wp_ajax_paynocchio_ajax_withdraw', [$this, 'paynocchio_ajax_withdraw']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_withdraw', [$this, 'paynocchio_ajax_withdraw']);

        $this->loader->add_action( 'woocommerce_add_to_cart_redirect', $plugin_public, 'redirect_checkout_add_cart' );

        $this->loader->add_filter( 'body_class', $plugin_public, 'paynocchio_body_class' );
    }

    /**
     * Handle Creation of the UUID
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_activation()
    {
        $nonce = isset( $_POST['ajax-activation-nonce'] ) ? sanitize_text_field( $_POST['ajax-activation-nonce'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'paynocchio_ajax_activation' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

        $this->set_uuid();

         if(!get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
             $wallet = new Woocommerce_Paynocchio_Wallet($this->get_uuid());
             $wallet_response = $wallet->createWallet();
             $json_response = json_decode($wallet_response);
             if($json_response->status === 'success') {
                 add_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, $json_response->wallet, true);
             } else {
                 wp_send_json([
                     'response' => $json_response,
                 ]);
             }
         }

         if($json_response->status === 'success') {
             wp_send_json_success();
         }
        wp_die();
    }


    /**
     * Handle Creation of the UUID
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_registration()
    {
        $nonce = isset( $_POST['ajax-registration-nonce'] ) ? sanitize_text_field( $_POST['ajax-registration-nonce'] ) : '';
        $user_email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'paynocchio_ajax_registration' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

        if($user_email) {
            $user_pass = wp_generate_uuid4();

            $info = array(
                'user_login'  =>  $user_email,
                'user_pass'  =>  $user_pass,
                'user_email' => $user_email,
            );
            $result = wp_insert_user( $info );

            if(is_wp_error( $result)) {
                wp_send_json_error([
                    'message' => $result->get_error_message()
                ]);

            } else {

                wp_set_current_user($result);
                wp_set_auth_cookie($result);

                wp_send_json_success();
            }

        }

        wp_die();
    }

    /**
     * Top Up Wallet
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_top_up()
    {
        $nonce = isset( $_POST['ajax-top-up-nonce'] ) ? sanitize_text_field( $_POST['ajax-top-up-nonce'] ) : '';
        $amount = isset( $_POST['amount'] ) ? sanitize_text_field( $_POST['amount'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'paynocchio_ajax_top_up' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }


         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
             $wallet = new Woocommerce_Paynocchio_Wallet($this->get_uuid());
             $wallet_response = $wallet->topUpWallet(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true), $amount);
         }

        wp_send_json([
            'response' => $wallet_response
        ]);
        wp_die();
    }

    /**
     * Top Up Wallet
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_set_status()
    {
        $nonce = isset( $_POST['ajax-status-nonce'] ) ? sanitize_text_field( $_POST['ajax-status-nonce'] ) : '';
        $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'paynocchio_ajax_set_status' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
             $wallet = new Woocommerce_Paynocchio_Wallet($this->get_uuid());
             $wallet_statuses = $wallet->getWalletStatuses();
             $walletId = get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true);
             $json_response = $wallet->updateWalletStatus($walletId, $wallet_statuses[$status]);

             if($json_response->status === 'success') {
                 wp_send_json_success();
             } else {
                 wp_send_json_error();
             }

             wp_die();
         }
    }

    /**
     * Delete Wallet from User Meta
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_delete_wallet()
    {
        $nonce = isset( $_POST['ajax-delete-nonce'] ) ? sanitize_text_field( $_POST['ajax-delete-nonce'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'paynocchio_ajax_delete_wallet' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {

             delete_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY);

             wp_send_json_success();

             wp_die();
         }
    }

    /**
     * Withdraw form Wallet
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_withdraw()
    {
        $nonce = isset( $_POST['ajax-withdraw-nonce'] ) ? sanitize_text_field( $_POST['ajax-withdraw-nonce'] ) : '';
        $amount = isset( $_POST['amount'] ) ? sanitize_text_field( $_POST['amount'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'paynocchio_ajax_withdraw' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }


         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
             $wallet = new Woocommerce_Paynocchio_Wallet($this->get_uuid());
             $wallet_response = $wallet->withdrawFromWallet(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true), $amount);
         }

        wp_send_json([
            'response' => $wallet_response
        ]);
        wp_die();
    }

    /**
     * Check Wallet Balance
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_get_user_wallet()
    {
         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {

             wp_send_json([
                 'walletId' => get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true)
             ]);
         } else {
             wp_send_json_error();
         }

        wp_die();
    }

    /**
     * Check Wallet Balance
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_check_balance()
    {
         if(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY)) {
             $wallet = new Woocommerce_Paynocchio_Wallet($this->get_uuid());
             $wallet_response = $wallet->getWalletBalance(get_user_meta($this->user_id, PAYNOCCHIO_WALLET_KEY, true));
         }

        wp_send_json([
            'response' => $wallet_response
        ]);

        wp_die();
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woocommerce_Paynocchio_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the current User UUID.
	 *
	 * @since     1.0.0
	 * @return    string    The UUID.
	 */
	public function get_uuid() {
		return get_user_meta($this->user_id, PAYNOCCHIO_USER_UUID_KEY, true);
	}

    /**
     * Setter for User meta
     *
     * @since     1.0.0
     * @return    void
     */
    public function set_uuid () {
	    if(!get_user_meta($this->user_id, PAYNOCCHIO_USER_UUID_KEY, true)){
            $uuid = wp_generate_uuid4();
            add_user_meta($this->user_id, PAYNOCCHIO_USER_UUID_KEY, $uuid, true);
        }
    }

    /**
     * User ID getter
     *
     * @since     1.0.0
     * @return    int
     */
    public function get_user_id(): int
    {
        return get_current_user_id();
    }

    /**
     * Get the current User Wallet data
     *
     * @since    1.0.0
     */
    public function get_paynocchio_wallet_info() {

        if (is_user_logged_in()) {
            $wallet = [];

            $current_user = wp_get_current_user();

            $wallet['user'] = [
                'first_name' => $current_user->first_name,
                'last_name' => $current_user->last_name];

            $user_paynocchio_wallet_id = get_user_meta($current_user->ID, PAYNOCCHIO_WALLET_KEY, true);

            if($user_paynocchio_wallet_id) {
                $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($current_user->ID);
                $wallet_bal_bon = $user_paynocchio_wallet->getWalletBalance($user_paynocchio_wallet_id);
                if($wallet_bal_bon) {
                    $wallet['balance'] = $wallet_bal_bon['balance'];
                    $wallet['bonuses'] = $wallet_bal_bon['bonuses'];
                    $wallet['card_number'] = $wallet_bal_bon['number'];
                    $wallet['status'] = $wallet_bal_bon['status'];
                }
            }

            return $wallet;
        }

        return false;
    }
}
