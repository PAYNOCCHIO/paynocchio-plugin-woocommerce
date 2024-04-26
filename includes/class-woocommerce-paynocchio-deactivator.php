<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://cfps.co
 * @since      1.0.0
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/includes
 * @author     CFP TECHNOLOGY <info@cfps.co>
 */
class Woocommerce_Paynocchio_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

	    $activation_page = get_page_by_path(WOOCOMMERCE_PAYNOCCHIO_ACTIVATION_PAGE_SLUG);
        wp_delete_post($activation_page->ID, true);

        // TODO: Delete all users meta

        if(get_user_meta(get_current_user_id(), PAYNOCCHIO_USER_UUID_KEY)) {
            delete_user_meta(get_current_user_id(), PAYNOCCHIO_USER_UUID_KEY);
        }

        if(get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
            delete_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY);
        }

        flush_rewrite_rules();

	}

}
