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
        $route     = 'wallet_balance/(?P<wallet_id>[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12})';
        //$route     = 'wallet_balance/(?P<id>\d+)';

        register_rest_route($namespace, $route, array(
            'methods'   => WP_REST_Server::READABLE,
            'callback'  => [$this, 'get_wallet_balance'],
            'permission_callback' => function () {
                return current_user_can( 'edit_others_posts' );
            }
        ));
    }

    /**
     * at_rest_testing_endpoint
     * @return WP_REST_Response
     */
    public function get_wallet_balance(WP_REST_Request $request)
    {
        $wallet_uuid = $request->get_param( 'wallet_id' );

        $user = get_users(array(
            'meta_key' => PAYNOCCHIO_WALLET_KEY,
            'meta_value' => $wallet_uuid,
            'number' => 1
        ));

        if(!$user) {
            return new WP_Error( 'no_wallet', 'User has no Wallet', array( 'status' => 404 ) );
        }

        $user_uuid = get_user_meta($user[0]->data->ID, 'user_uuid', true);

        if($user_uuid) {
            $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($user_uuid);
            $wallet_response = $user_paynocchio_wallet->getWalletBalance($wallet_uuid);
            return new WP_REST_Response($wallet_response);
        }

    }

}
