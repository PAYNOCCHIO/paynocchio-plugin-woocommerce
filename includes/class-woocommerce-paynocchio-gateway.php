<?php

class Woocommerce_Paynocchio_Payment_Gateway extends WC_Payment_Gateway {

    private $testmode;

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


        $this->title       = $this->get_option( 'title' );

        $this->enabled     = $this->get_option( 'enabled' );

        $this->description     = $this->get_option( 'description' );

        // Turn these settings into variables we can use
        /*foreach ( $this->settings as $setting_key => $value ) {
            $this->$setting_key = $value;
        }*/

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
            'api_login' => array(
                'title'		=> __( 'Paynocchio API Login', 'paynocchio' ),
                'type'		=> 'text',
                'desc_tip'	=> __( 'This is the API Login provided by Paynocchio when you signed up for an account.', 'paynocchio' ),
            ),
            'trans_key' => array(
                'title'		=> __( 'Paynocchio Transaction Key', 'paynocchio' ),
                'type'		=> 'password',
                'desc_tip'	=> __( 'This is the Transaction Key provided by Paynocchio when you signed up for an account.', 'paynocchio' ),
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

        // Decide which URL to post to
        $environment_url = $this->testmode
            ? 'https://secure.Paynocchio/gateway/transact.dll'
            : 'https://test.Paynocchio/gateway/transact.dll';

        // This is where the fun stuff begins
        $payload = array(
            // Paynocchio Credentials and API Info
            "x_tran_key"           	=> $this->trans_key,
            "x_login"              	=> $this->api_login,
            "x_version"            	=> "3.1",

            // Order total
            "x_amount"             	=> $customer_order->order_total,

            // Credit Card Information
            "x_card_num"           	=> str_replace( array(' ', '-' ), '', $_POST['paynocchio-card-number'] ),
            "x_card_code"          	=> ( isset( $_POST['paynocchio-card-cvc'] ) ) ? $_POST['paynocchio-card-cvc'] : '',
            "x_exp_date"           	=> str_replace( array( '/', ' '), '', $_POST['paynocchio-card-expiry'] ),

            "x_type"               	=> 'AUTH_CAPTURE',
            "x_invoice_num"        	=> str_replace( "#", "", $customer_order->get_order_number() ),
            "x_test_request"       	=> $this->testmode,
            "x_delim_char"         	=> '|',
            "x_encap_char"         	=> '',
            "x_delim_data"         	=> "TRUE",
            "x_relay_response"     	=> "FALSE",
            "x_method"             	=> "CC",

            // Billing Information
            "x_first_name"         	=> $customer_order->billing_first_name,
            "x_last_name"          	=> $customer_order->billing_last_name,
            "x_address"            	=> $customer_order->billing_address_1,
            "x_city"              	=> $customer_order->billing_city,
            "x_state"              	=> $customer_order->billing_state,
            "x_zip"                	=> $customer_order->billing_postcode,
            "x_country"            	=> $customer_order->billing_country,
            "x_phone"              	=> $customer_order->billing_phone,
            "x_email"              	=> $customer_order->billing_email,

            // Shipping Information
            "x_ship_to_first_name" 	=> $customer_order->shipping_first_name,
            "x_ship_to_last_name"  	=> $customer_order->shipping_last_name,
            "x_ship_to_company"    	=> $customer_order->shipping_company,
            "x_ship_to_address"    	=> $customer_order->shipping_address_1,
            "x_ship_to_city"       	=> $customer_order->shipping_city,
            "x_ship_to_country"    	=> $customer_order->shipping_country,
            "x_ship_to_state"      	=> $customer_order->shipping_state,
            "x_ship_to_zip"        	=> $customer_order->shipping_postcode,

            // information customer
            "x_cust_id"            	=> $customer_order->user_id,
            "x_customer_ip"        	=> $_SERVER['REMOTE_ADDR'],

        );

        // Send this payload to Paynocchio for processing
        $response = wp_remote_post( $environment_url, array(
            'method'    => 'POST',
            'body'      => http_build_query( $payload ),
            'timeout'   => 90,
            'sslverify' => false,
        ) );

        if ( is_wp_error( $response ) )
            throw new Exception( __( 'There is issue for connectin payment gateway. Sorry for the inconvenience.', 'paynocchio' ) );

        if ( empty( $response['body'] ) )
            throw new Exception( __( 'Paynocchio\'s Response was not get any data.', 'paynocchio' ) );

        // get body response while get not error
        $response_body = wp_remote_retrieve_body( $response );

        foreach ( preg_split( "/\r?\n/", $response_body ) as $line ) {
            $resp = explode( "|", $line );
        }

        // values get
        $r['response_code']             = $resp[0];
        $r['response_sub_code']         = $resp[1];
        $r['response_reason_code']      = $resp[2];
        $r['response_reason_text']      = $resp[3];

        // 1 or 4 means the transaction was a success
        if ( ( $r['response_code'] == 1 ) || ( $r['response_code'] == 4 ) ) {
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
            wc_add_notice( $r['response_reason_text'], 'error' );
            $customer_order->add_order_note( 'Error: '. $r['response_reason_text'] );
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

        if ( !is_user_logged_in() ) {
            echo do_shortcode('[paynocchio_registration_block]');
        } else {
            echo do_shortcode('[paynocchio_activation_block]');
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