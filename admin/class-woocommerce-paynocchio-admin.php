<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cfps.co
 * @since      1.0.0
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/admin
 * @author     CFP TECHNOLOGY <info@cfps.co>
 */
class Woocommerce_Paynocchio_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/css/admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Paynocchio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Paynocchio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'dist/js/admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function paynocchio_gateway_block_support() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Paynocchio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Paynocchio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {

            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-paynocchio-gateway-blocks-support.php';

            add_action(
                'woocommerce_blocks_payment_method_type_registration',
                function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
                    $payment_method_registry->register( new Woocommerce_Paynocchio_Gateway_Blocks_Support() );
                }
            );
        }
	}

}
