<?php

class Woocommerce_Paynocchio_Payment_Gateway extends WC_Payment_Gateway {

    private bool $testmode;

    function __construct() {

        // global ID
        $this->id = "paynocchio";

        // Show Title
        $this->method_title = __( "Paynocchio", 'paynocchio' );

        // Show Description
        $this->method_description = __( "Paynocchio Payment Gateway Plug-in for WooCommerce", 'paynocchio' );

        // vertical tab title
        $this->title        = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );

        $this->icon = null;

        $this->has_fields = true;

        // support default form with credit card
        $this->supports = array( 'product', 'refunds' );

        // setting defines
        $this->init_form_fields();

        // load time variable setting
        $this->init_settings();

        $this->testmode = 'yes' === $this->get_option( 'testmode' );

        $this->enabled = $this->get_option( 'enabled' );

        // further check of SSL if you want
        //add_action( 'admin_notices', array( $this,	'do_ssl_check' ) );

        // Save settings
        if ( is_admin() ) {
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        }

        add_action( 'woocommerce_api_paynocchio', array( $this, 'webhook' ) );

    } // Here is the  End __construct()

    // administration fields for specific Gateway
    public function init_form_fields() {

        $this->form_fields = array(
            'enabled' => array(
                'title'		=> __( 'Enable / Disable', 'paynocchio' ),
                'label'		=> __( 'Enable this payment gateway', 'paynocchio' ),
                'type'		=> 'checkbox',
                'default'	=> 'no',
            ),
            'title' => array(
                'title'		=> __( 'Title', 'paynocchio' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'Payment title of checkout process.', 'paynocchio' ),
                'default'	=> __( 'Paynocchio pay', 'paynocchio' ),
            ),
            'description' => array(
                'title'		=> __( 'Description', 'paynocchio' ),
                'type'		=> 'textarea',
                'desc_tip'	=> __( 'Payment title of checkout process.', 'paynocchio' ),
                'default'	=> __( 'Successfully payment through Paynocchio.', 'paynocchio' ),
                'css'		=> 'max-width:450px;'
            ),
            'base_url' => array(
                'title'		=> __( 'Paynocchio base url', 'paynocchio' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the base url provided by Paynocchio when you signed up for an account.', 'paynocchio' ),
                'default'   => 'https://wallet.stage.paynocchio.com'
            ),
            PAYNOCCHIO_ENV_KEY => array(
                'title'		=> __( 'Paynocchio Environment UUID', 'paynocchio' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the environment_uuid provided by Paynocchio when you signed up for an account.', 'paynocchio' ),
            ),
            PAYNOCCHIO_SECRET_KEY => array(
                'title'		=> __( 'Paynocchio Secret UUID', 'paynocchio' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the Secret UUID provided by Paynocchio when you signed up for an account.', 'paynocchio' ),
            ),
            PAYNOCCHIO_CURRENCY_KEY => array(
                'title'		=> __( 'Paynocchio Currency UUID', 'paynocchio' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the currency UUID provided by Paynocchio when you signed up for an account.', 'paynocchio' ),
            ),
            PAYNOCCHIO_TYPE_KEY => array(
                'title'		=> __( 'Paynocchio Type UUID', 'paynocchio' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the Type UUID provided by Paynocchio when you signed up for an account.', 'paynocchio' ),
            ),
            PAYNOCCHIO_STATUS_KEY => array(
                'title'		=> __( 'Paynocchio Status UUID', 'paynocchio' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the Status UUID provided by Paynocchio when you signed up for an account.', 'paynocchio' ),
            ),
            'testmode' => array(
                'title'		=> __( 'Paynocchio Test Mode', 'paynocchio' ),
                'label'		=> __( 'Enable Test Mode', 'paynocchio' ),
                'type'		=> 'checkbox',
                'description' => __( 'This is the test mode of gateway.', 'paynocchio' ),
                'default'	=> 'no',
            ),

        );
    }

    // Response handled for payment gateway
    public function process_payment( $order_id )
    {
        global $woocommerce;

        $customer_order = new WC_Order($order_id);

        //$order_id = $customer_order->get_order_number();

        $order_uuid = wp_generate_uuid4();
        $customer_order->update_meta_data('uuid', $order_uuid);

        $user_wallet_id = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);
        $user_uuid = get_user_meta($customer_order->get_user_id(), 'user_uuid', true);
        $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($user_uuid);

        $fullAmount = $customer_order->total;
        $amount = $customer_order->total;

        $bonusAmount = ( isset( $_POST['bonusesvalue'] ) ) ? $_POST['bonusesvalue'] : null;

        $customer_order->update_meta_data('bonuses_value', $bonusAmount);

        if(!$bonusAmount) {
            $fullAmount = null;
        } else {
            $amount = $fullAmount - $bonusAmount;
        }

        $wallet_response = $user_paynocchio_wallet->getWalletBalance(get_user_meta($customer_order->user_id, PAYNOCCHIO_WALLET_KEY, true));
        $response = $user_paynocchio_wallet->makePayment($user_wallet_id, $fullAmount, $amount, $order_uuid, $bonusAmount);

        //TODO: Works only first fire!
        if ($wallet_response['balance'] + $wallet_response['bonuses'] < $amount) {
            wc_add_notice( 'You balance is lack for $' . $amount - $wallet_response['balance'] . '. Please TopUp.', 'error' );
            $customer_order->add_order_note( 'Error: insufficient funds' );
            return;
        }

        if ( $response['status_code'] === 200) {

            // Payment successful
            $customer_order->add_order_note( __( 'Paynocchio complete payment.', 'paynocchio' ) );

            // paid order marked
            $customer_order->payment_complete();

            /**
             * Set COMPLETED status for Orders
             */
            $customer_order->update_status( "completed" );

            // this is important part for empty cart
            $woocommerce->cart->empty_cart();
            // Redirect to thank you page
            //print_r($bonusAmount);
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url( $customer_order ),
            );
        } else {
            //transiction fail
            wc_add_notice( 'Please try again.', 'error' );
            $customer_order->add_order_note( 'Error: '. json_decode($response['detail'])->msg );
        }

    }

    public function process_refund($order_id, $amount = null, $reason = '') {

        $customer_order = new WC_Order($order_id);
       // $wallet_id = get_user_meta($customer_order->get_user_id(), 'paynoccio_wallet', true);
       // $user_uuid = get_user_meta($customer_order->get_user_id(), 'user_uuid', true);
        $order_uuid = $customer_order->get_meta( 'uuid' , true );
        $bonuses_value = $customer_order->get_meta( 'bonuses_value' , true );

        $user_wallet_id = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);
        $user_uuid = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);

        $order_uuid = get_post_meta( $customer_order , 'uuid' , true );

        $customer_order->add_order_note( 'User UUID ' . $user_uuid );
        $customer_order->add_order_note( 'Wallet UUID ' . $user_wallet_id );
        $customer_order->add_order_note( 'Order UUID ' . $order_uuid );

        if ($bonuses_value) {
            $amount = $amount - $bonuses_value;
        }

        $wallet_response = $user_paynocchio_wallet->chargeBack($order_uuid, $user_wallet_id, $amount);

        /*print_r([
            'order' => $order_uuid,
            'wallet' => $user_wallet_id,
            '$user_uuid' => $user_uuid,
            '$amount' => $amount,
            '$wallet_response' => $wallet_response,
        ]);*/

        /*$customer_order->add_order_note( __( 'Paynocchio complete refund.', 'paynocchio' ) );
        return true;*/

        if ( $wallet_response['status_code'] === 200) {
            // Refund successful
            $customer_order->add_order_note( __( 'Paynocchio complete refund.', 'paynocchio' ) );
            return true;
        } else {
            // Refund fail
            $customer_order->add_order_note( 'Paynocchio refund error: '. json_decode($wallet_response['detail'])->msg );
            return false;
        }
    }

    // Validate fields
    public function validate_fields() {
        return false;
    }

    /**
     * You will need it if you want your custom credit card form, Step 4 is about it
     */
    public function payment_fields() {

        /*
         * Display description above form
         * */
        if( $this->description ) {
            // you can instructions for test mode, I mean test card numbers etc.
            if( $this->testmode ) {
                $this->description .= '<p style="color:#1db954;font-weight:bold">TEST MODE ENABLED.</p>';
            }
            // display the description with <p> tags etc.
            echo wpautop( wp_kses_post( $this->description ) );
        }

        if(is_user_logged_in()) {
            if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
                echo do_shortcode('[paynocchio_activation_block register_redirect="/checkout?ans=checkemail" login_redirect="/checkout#payment_method_paynocchio"]');
            } else {
                echo do_shortcode('[paynocchio_payment_widget]');
            }
        } else {
            echo do_shortcode('[paynocchio_registration_block 
            register_redirect="/checkout?ans=checkemail" 
            login_redirect="/checkout#payment_method_paynocchio"]');
        }


    }

    /*public function do_ssl_check() {
        if( $this->enabled == "yes" ) {
            if( get_option( 'woocommerce_force_ssl_checkout' ) == "no" ) {
                echo "<div class=\"error\"><p>". sprintf( __( "<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>" ), $this->method_title, admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) ."</p></div>";
            }
        }
    }*/

}