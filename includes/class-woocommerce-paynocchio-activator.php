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

        // Check if the page already exists
        if(!get_page_by_path(WOOCOMMERCE_PAYNOCCHIO_ACTIVATION_PAGE_SLUG)) {
            $page = wp_insert_post(
                array(
                    'comment_status' => 'close',
                    'ping_status'    => 'close',
                    'post_author'    => 1,
                    'post_title'     => 'Kopybara.Pay',
                    'post_name'      => strtolower(str_replace(' ', '-', trim(WOOCOMMERCE_PAYNOCCHIO_ACTIVATION_PAGE_SLUG))),
                    'post_status'    => 'publish',
                    //'post_content'   =>  include( WP_PLUGIN_DIR.'/'.plugin_dir_path(WOOCOMMERCE_PAYNOCCHIO_BASENAME) . 'views/paynocchio-activation-page-content.php')
                    'post_content'   =>  'Hello world'
            ,
                    'post_type'      => 'page',
                )
            );
        }
	}
}
