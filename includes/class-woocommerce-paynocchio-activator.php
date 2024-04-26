<?php

/**
 * Fired during plugin activation
 *
 * @link       https://cfps.co
 * @since      1.0.0
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/includes
 * @author     CFP TECHNOLOGY <info@cfps.co>
 */

class Woocommerce_Paynocchio_Activator {

	/**
	 * Actions on plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

    /**
     * This action hook registers our PHP class as a WooCommerce payment gateway
     */
        if( !class_exists( 'WooCommerce' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( 'Please install and Activate WooCommerce first.', 'woocommerce-paynocchio' ), 'Plugin dependency check', array( 'back_link' => true ) );
        }

        flush_rewrite_rules();
	}
}
