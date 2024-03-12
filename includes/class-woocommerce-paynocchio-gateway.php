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
        $this->title = __( "Paynocchio", 'paynocchio' );

        $this->icon = null;

        $this->has_fields = true;

        // support default form with credit card
        $this->supports = array( 'product' );

        // setting defines
        $this->init_form_fields();

        // load time variable setting
        $this->init_settings();

        $this->testmode = 'yes' === $this->get_option( 'testmode' );

        $this->enabled = $this->get_option( 'enabled' );

        $this->description = $this->get_option( 'description' );

        // further check of SSL if you want
        add_action( 'admin_notices', array( $this,	'do_ssl_check' ) );

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
    public function process_payment( $order_id ) {
        global $woocommerce;

        $customer_order = new WC_Order( $order_id );

        $order_id = $customer_order->get_order_number();

        $order_uuid = wp_generate_uuid4();
        $customer_order->update_meta_data( 'uuid', $order_uuid );

        $user_wallet_id = get_user_meta($customer_order->get_user_id(), 'paynoccio_wallet', true);
        $user_uuid = get_user_meta($customer_order->get_user_id(), 'user_uuid', true);
        $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($user_uuid);

        $fullAmount = $customer_order->get_total();
        //$amount = (isset( $_POST['paynocchio-amount'] ) ) ? $_POST['paynocchio-amount'] : '';
        $amount = $fullAmount;
        //$bonusAmount = ( isset( $_POST['paynocchio-bonus_amount'] ) ) ? $_POST['paynocchio-bonus_amount'] : '';
        $bonusAmount = null;

        //$wallet_response = $user_paynocchio_wallet->getWalletBalance(get_user_meta($customer_order->user_id, 'paynoccio_wallet', true));
        $response = $user_paynocchio_wallet->makePayment($user_wallet_id, $fullAmount, $amount, $order_uuid, $bonusAmount);

        //print_r($response);

        if ( $response['status_code'] !== 200)
            throw new Exception( __( 'There is issue for connection payment gateway. Sorry for the inconvenience.', 'paynocchio' ) );


        if ( $response['status_code'] === 200) {
            // Payment successful
            $customer_order->add_order_note( __( 'Paynocchio complete payment.', 'paynocchio' ) );

            // paid order marked
            $customer_order->payment_complete();

            // this is important part for empty cart
            $woocommerce->cart->empty_cart();

            // Redirect to thank you page
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
            if (!get_user_meta(get_current_user_id(), 'paynoccio_wallet')) {
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

    public function do_ssl_check() {
        if( $this->enabled == "yes" ) {
            if( get_option( 'woocommerce_force_ssl_checkout' ) == "no" ) {
                echo "<div class=\"error\"><p>". sprintf( __( "<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>" ), $this->method_title, admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) ."</p></div>";
            }
        }
    }

}