<?php

/**
 * Adds custom RESTapi routes to Woocommerce
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
class Woocommerce_Paynocchio_Add_RESTapi_Routes {

	/**
	 * Add the routes
	 *
	 * @since    1.0.0
	 */
	public function add_custom_routes() {

        $namespace = 'api-paynocchio/v2';
        $current_user_route = 'wallet/current_user';
        //$route     = 'wallet_balance/(?P<wallet_id>[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12})';
        //$route     = 'wallet_balance/(?P<id>\d+)';

        register_rest_route($namespace, $current_user_route, array(
            'methods'   => WP_REST_Server::READABLE,
            'callback'  => [$this, 'get_current_user_wallet_balance'],
        ));
    }

    /**
     * at_rest_testing_endpoint
     * @return WP_REST_Response
     */
    public function get_current_user_wallet_balance()
    {

        $user_id = get_current_user_id();

        $user_uuid = get_user_meta($user_id, PAYNOCCHIO_USER_UUID_KEY, true);

        if($user_uuid) {
            $wallet_uuid = get_user_meta($user_id, PAYNOCCHIO_WALLET_KEY, true);
            if($wallet_uuid) {
                $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($user_uuid);
                $wallet_response = $user_paynocchio_wallet->getWalletBalance($wallet_uuid);
                return new WP_REST_Response($wallet_response);
            }
        }

    }

}
