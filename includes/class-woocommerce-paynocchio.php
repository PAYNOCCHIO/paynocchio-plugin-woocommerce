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
     * Current user object
     *
     * @since    1.0.0
     * @access   protected
     * @var      $user   Current user.
     */
    private $user;

    /**
     * Current user Paynocchio Wallet
     *
     * @since    1.0.0
     * @access   protected
     * @var      $wallet  Woocommerce_Paynocchio_Wallet instanse.
     */
    private $wallet;

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
		$this->add_shortcodes();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function init()
    {
        add_action('init', function(){
            $this->is_user_logged_in = is_user_logged_in();
            if($this->is_user_logged_in) {
                $this->user = wp_get_current_user();
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

		/**
		 * The class responsible for defining Paynocchio Wocommerce payment Gateway.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-add-gateway.php';

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

        if(!$this->is_user_logged_in) {
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'ajax_login_init' );
            add_action( 'wp_ajax_nopriv_paynocchio_ajax_login', [$this, 'paynocchio_ajax_login']);
        }

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'ajax_activation_init' );
        add_action( 'wp_ajax_paynocchio_ajax_activation', [$this, 'paynocchio_ajax_activation']);
        add_action( 'wp_ajax_nopriv_paynocchio_ajax_activation', [$this, 'paynocchio_ajax_activation']);

       // add_action( 'user_register', [$this, 'set_user_uuid']);
	}

    /**
     * Handle Registration and Login forms.
     *
     * @since    1.0.0
     */
    public function paynocchio_ajax_login()
    {
        $nonce = isset( $_POST['ajax-login-nonce'] ) ? sanitize_text_field( $_POST['ajax-login-nonce'] ) : '';

        if ( ! wp_verify_nonce( $nonce, 'ajax-login-nonce' ) ) {
            wp_send_json( array(
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Nonce verification failed',
            ) );
            wp_die();
        }

        wp_send_json( array(
            'status'  => 'success',
            'title'   => 'Success',
            'message' => 'Success.',
        ) );
        wp_die();
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

        if(!get_user_meta($this->user->ID, PAYNOCCHIO_USER_UUID_KEY)) {
            $user_uuid = wp_generate_uuid4();
            add_user_meta($this->user->ID, PAYNOCCHIO_USER_UUID_KEY, $user_uuid, true);
        }

        if(!get_user_meta($this->user->ID, 'paynoccio_wallet')) {
            $wallet = new Woocommerce_Paynocchio_Wallet($this->get_uuid());
            add_user_meta($this->user->ID, 'paynoccio_wallet', $wallet->createWallet(), true);
        }

        wp_send_json( array(
            'status'  => 'success',
            'title'   => 'Success',
            'message' => 'Success.',
            'response' => $wallet->createWallet(),
        ) );
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
		return get_user_meta($this->user->ID, PAYNOCCHIO_USER_UUID_KEY);
	}

	/**
	 * Retrieve the current User UUID.
	 *
	 * @since     1.0.0
	 * @return    string    The UUID.
	 */
	public function get_wallet() {
		return $this->wallet;
	}

}
