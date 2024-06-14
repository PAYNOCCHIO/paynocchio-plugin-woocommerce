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
        $testing = 'testing';
        $current_user_route = 'wallet/current_user';
        $update_order_status_route = 'order/update';
        $wallet_topup = 'wallet/topup';
        //$route     = 'wallet_balance/(?P<wallet_id>[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12})';
        //$route     = 'wallet_balance/(?P<id>\d+)';

        register_rest_route($namespace, $current_user_route, [
            'methods'   => WP_REST_Server::READABLE,
            'callback'  => [$this, 'get_current_user_wallet_balance'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($namespace, $update_order_status_route, [
            'methods'   => 'POST',
            'callback'  => [$this, 'update_order_status'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($namespace, $wallet_topup, [
            'methods'   => 'POST',
            'callback'  => [$this, 'wallet_topup'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($namespace, $testing, [
            'methods'   => 'POST',
            'callback'  => [$this, 'testing'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * get_current_user_wallet_balance
     * @return WP_REST_Response
     */
    public function get_current_user_wallet_balance()
    {

        $user_id = get_current_user_id();

        if(!$user_id) {
            return new WP_Error( 'no_user', 'Invalid user', array( 'status' => 404 ) );
        }

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

    /**
     * update_order_status
     * @return WP_REST_Response
     */
    public function update_order_status($request)
    {
        $parameters = $request->get_params();

        if(!$parameters) {
            return new WP_Error( 'no_params', 'Invalid parameters', array( 'status' => 404 ) );
        }

        if($parameters['external_order_uuid']) {

            $args = array(
                'meta_key'      => 'order_uuid', // Postmeta key field
                'meta_value'    => $parameters['external_order_uuid'], // Postmeta value field
                'meta_compare'  => '=', // Possible values are ‘=’, ‘!=’, ‘>’, ‘>=’, ‘<‘, ‘<=’, ‘LIKE’, ‘NOT LIKE’, ‘IN’, ‘NOT IN’, ‘BETWEEN’, ‘NOT BETWEEN’, ‘EXISTS’ (only in WP >= 3.5), and ‘NOT EXISTS’ (also only in WP >= 3.5). Values ‘REGEXP’, ‘NOT REGEXP’ and ‘RLIKE’ were added in WordPress 3.7. Default value is ‘=’.
                'return'        => 'ids' // Accepts a string: 'ids' or 'objects'. Default: 'objects'.
            );

            $order_id = wc_get_orders( $args );

            $customer_order = new WC_Order($order_id[0]);

            $customer_order->update_status('pending');

            if(!$customer_order) {
                return new WP_Error( 'no_order', 'Invalid Order number', array( 'status' => 404 ) );
            }

            /**
             * Set COMPLETED status for Order
             */
            if($parameters['status_type']) {
                if($parameters['status_type'] === 'complete') {
                    $customer_order->update_status('completed');
                }
            } else {
                return new WP_Error( 'no_status', 'Invalid Status value', array( 'status' => 404 ) );
            }

            return new WP_REST_Response( true, 200 );
        }

    }

    /**
     * wallet top up webhook
     * @return WP_REST_Response
     */
    public function wallet_topup($request)
    {
        $parameters = $request->get_params();

        if(!$parameters) {
            return new WP_Error( 'no_params', 'Invalid parameters', array( 'status' => 404 ) );
        }

        if($parameters['wallet_uuid'] && $parameters['amount']) {

            $args = array(
                'meta_key'      => 'wallet_uuid',
                'meta_value'    => $parameters['wallet_uuid'],
            );

            $users = get_users($args);

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            foreach ($users as $user) {
                $message = "<b>Dear! ".$user->name."</b>,<br/><br/> Your wallet has been topped up for $".$parameters['amount']." successfuly.<br>this is an automated mail.pls  don't reply to this mail. ";
                wp_mail( $user->user_email, "Top up is complete!", $message, $headers );
            }

            return new WP_REST_Response( true, 200 );
        }

    }

    /**
     * update_order_status
     * @return WP_REST_Response
     */
    public function testing($request)
    {
        $parameters = $request->get_params();
        return new WP_REST_Response( $parameters, 200 );
    }

}
