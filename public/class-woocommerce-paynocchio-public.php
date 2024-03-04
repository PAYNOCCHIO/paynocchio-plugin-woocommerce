<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cfps.co
 * @since      1.0.0
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/public
 * @author     CFP TECHNOLOGY <info@cfps.co>
 */
class Woocommerce_Paynocchio_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Paynocchio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Paynocchio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/css/public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Paynocchio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Paynocchio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'dist/public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the Ajax Login script.
	 *
	 * @since    1.0.0
	 */
	public function ajax_login_init() {

            wp_register_script('paynocchio-ajax-login-script', plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'dist/ajax.js', [] );
            wp_enqueue_script('paynocchio-ajax-login-script');

            wp_localize_script( 'paynocchio-ajax-login-script', 'paynocchio_login_object', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'redirecturl' => '/checkout',
                'loadingmessage' => __('Loading')
            ));

	}
}