<?php

/**
 * Adds custom gateway to Woocommerce
 *
 *
 * @link       https://cfps.co
 * @since      1.0.0
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/includes
 *
 * @author     CFP TECHNOLOGY <info@cfps.co>
 */
class Woocommerce_Paynocchio_Add_Gateway {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function add_woocommerce_gateway() {

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-paynocchio-gateway.php';

        add_filter( 'woocommerce_payment_gateways', 'woocommerce_paynocchio_add_gateway' );

        function woocommerce_paynocchio_add_gateway( $methods ) {
            $methods[] = 'Woocommerce_Paynocchio_Payment_Gateway';
            return $methods;
        }
    }



}
