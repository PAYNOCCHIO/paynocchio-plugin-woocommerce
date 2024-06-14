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

        add_action( 'woocommerce_api_paynocchio', array( $this, 'webhook' ));
        add_action( 'update_option', array( $this,	'do_health_check' ), 10, 3 );

    } // Here is the  End __construct()

    public function get_test_mode()
    {
        return $this->testmode;
    }

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
            'testmode' => array(
                'title'		=> __( 'Paynocchio Test Mode', 'paynocchio' ),
                'label'		=> __( 'Enable Test Mode', 'paynocchio' ),
                'type'		=> 'checkbox',
                'description' => __( 'This is the test mode of gateway.', 'paynocchio' ),
                'default'	=> 'no',
            ),
            'darkmode' => array(
                'title'		=> __( 'Paynocchio Dark Mode', 'paynocchio' ),
                'label'		=> __( 'Enable Dark mode', 'paynocchio' ),
                'type'		=> 'checkbox',
                'description' => __( 'If you select this checkbox, Paynocchio pages and modules will be displayed with a dark theme.', 'paynocchio' ),
                'default'	=> 'no',
            ),
            'rounded' => array(
                'title'		=> __( 'Paynocchio Rounded Mode', 'paynocchio' ),
                'label'		=> __( 'Enable Rounded mode', 'paynocchio' ),
                'type'		=> 'checkbox',
                'description' => __( 'If you select this checkbox, the Paynocchio interface elements will be rounded off.', 'paynocchio' ),
                'default'	=> 'no',
            ),
            'accent_color' => array(
                'title'		=> __( 'Paynocchio Accent Color', 'paynocchio' ),
                'label'		=> __( 'Enter accent color', 'paynocchio' ),
                'type'		=> 'color',
                'description' => __( 'Specify the accent color of the interface elements, such as buttons. Leave blank for default.', 'paynocchio' ),
            ),
            'accent_text_color' => array(
                'title'		=> __( 'Paynocchio Text Accent Color', 'paynocchio' ),
                'label'		=> __( 'Enter text accent color', 'paynocchio' ),
                'type'		=> 'color',
                'description' => __( 'Specify the text color for accent elements of the interface, such as buttons. Leave blank for default.', 'paynocchio' ),
            ),
            'embleme_url' => array(
                'title'		=> __( 'Embleme URL for Cards', 'paynocchio' ),
                'label'		=> __( 'Embleme URL', 'paynocchio' ),
                'type'		=> 'url',
                'description' => __( 'Enter the URL of the logo that will be displayed on top of the card image on the payment and personal account pages. The aspect ratio is 1 to 1!', 'paynocchio' ),
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
        $customer_order->update_meta_data('order_uuid', $order_uuid);

        $user_wallet_id = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);
        $user_uuid = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);
        $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($user_uuid);

        $paynocchio = new Woocommerce_Paynocchio();
        $wallet = $paynocchio->get_paynocchio_wallet_info();

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

        /*wp_send_json([
            'balance' => $wallet_response['balance'],
            'bonuses' => $wallet_response['bonuses'],
        ]);*/

        if ($wallet['code'] !== 500) {

            if ($wallet_response['balance'] + $wallet_response['bonuses'] < $customer_order->total) {
                wc_add_notice('You balance is lack for $' . $customer_order->total - $wallet_response['balance'] . '. Please top up your wallet.', 'error');
                $customer_order->add_order_note('Error: insufficient funds');
                return;
            }

            if ($wallet_response['balance'] + $wallet_response['bonuses'] >= $customer_order->total && ($wallet_response['balance'] + $bonusAmount) < $customer_order->total) {
                wc_add_notice('Please TopUp or use your Bonuses.', 'error');
                $customer_order->add_order_note('Error: insufficient funds');
                return;
            }

            if ($response['status_code'] === 200) {

                // Payment successful
                $customer_order->add_order_note(__('Paynocchio complete payment.', 'paynocchio'));

                // paid order marked
                $customer_order->payment_complete();

                /**
                 * Set COMPLETED status for Orders
                 */
                //$customer_order->update_status( "completed" );

                // this is important part for empty cart
                $woocommerce->cart->empty_cart();
                // Redirect to thank you page
                //print_r($bonusAmount);
                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url($customer_order),
                );
            } else {
                //transiction fail
                wc_add_notice('Please try again.', 'error');
                $customer_order->add_order_note('Error: ' . json_decode($response['detail'])->msg);
            }
        } else {
            wc_add_notice('An error in the operation of the wallet system. Please contact support.', 'error');
            $customer_order->add_order_note('Error: Internal Server Error');
            return;
        }

    }

    public function process_refund($order_id, $amount = null, $reason = '') {

        $customer_order = new WC_Order($order_id);
        $order_uuid = $customer_order->get_meta( 'order_uuid' , true );
        $bonuses_value = $customer_order->get_meta( 'bonuses_value' , true );

        $user_wallet_id = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);
        $user_uuid = get_user_meta($customer_order->get_user_id(), PAYNOCCHIO_WALLET_KEY, true);

        $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($user_uuid);

        $customer_order->add_order_note( 'User UUID ' . $user_uuid );
        $customer_order->add_order_note( 'Wallet UUID ' . $user_wallet_id );
        $customer_order->add_order_note( 'Order UUID ' . $order_uuid );

        if ($bonuses_value) {
            $amount = $amount - $bonuses_value;
        }

        $wallet_response = $user_paynocchio_wallet->chargeBack($order_uuid, $user_wallet_id, $amount);

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

        return true;
    }

    /**
     * You will need it if you want your custom credit card form, Step 4 is about it
     */
    public function payment_fields() {

        $paynocchio = new Woocommerce_Paynocchio();
        $wallet = $paynocchio->get_paynocchio_wallet_info();

        /*
         * Display description above form
         * */
            if( $this->description ) {
                // you can instructions for test mode, I mean test card numbers etc.
                if( $this->get_test_mode() ) {
                    $this->description .= '<p style="color:#1db954;font-weight:bold">TEST MODE ENABLED.</p>';
                }
                // display the description with <p> tags etc.
                echo wpautop( wp_kses_post( $this->description ) );
            }

            if(is_user_logged_in()) {
                if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
                    echo do_shortcode('[paynocchio_activation_block register_redirect="/checkout?step=2" login_redirect="/checkout?step=2"]');
                } else {
                    if($wallet['code'] !== 500) {
                        echo do_shortcode('[paynocchio_payment_widget]');
                    } else {
                        echo '<div class="paynocchio_error_notification">
                        <svg class="cfps-max-w-[100px] cfps-mx-auto cfps-mb-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm0-8v6h2V7h-2z" fill="#d4d4d4" class="fill-000000"></path>
                        </svg>
                        <div class="cfps-mb-4">
                            If you see this page, our wallet service issues an error.
                        </div>
                        <div>To solve the problem, please contact <b>'.get_bloginfo('name').'</b> support</div>
                    </div>';
                    }
                }
            } else {
                echo do_shortcode('[paynocchio_registration_block 
            register_redirect="/checkout?step=2&ans=checkemail" 
            login_redirect="/checkout#payment_method_paynocchio"]');
            }
    }

    public function do_health_check($option_name, $old_value, $new_value) {

        if($option_name === 'woocommerce_paynocchio_settings') {
            if(!wp_is_uuid($new_value[PAYNOCCHIO_ENV_KEY])) {
                add_action( 'admin_notices', function(){
                    echo '<div class="notice notice-error"><p>Please check if Environment ID is correct</p></div>';
                } );

                return;
            }
            if(!$new_value[PAYNOCCHIO_SECRET_KEY]) {
                add_action( 'admin_notices', function(){
                    echo '<div class="notice notice-error"><p>Please check if Secret Key is correct</p></div>';
                } );
            }

            add_action( 'admin_notices', array( $this,	'make_notice') );
        }
    }

    public function make_notice() {
        $fake_uuid = wp_generate_uuid4();
        $wallet = new Woocommerce_Paynocchio_Wallet($fake_uuid);
        $response = $wallet->healthCheck();
        $json_response = json_decode($response);

        if($json_response->status === 'success') {
            update_option('woocommerce_paynocchio_approved', true);
            echo "<div class=\"notice notice-success is-dismissible\"><p>". sprintf( __( "Integration with Paynocchio succeeded. Response is <strong>%s</strong> and status code is <strong>%s</strong>" ), $json_response->status, $json_response->message) ."</p></div>";
        } else {
            update_option('woocommerce_paynocchio_approved', false);
            echo "<div class=\"notice notice-error is-dismissible\"><p>". sprintf( __( "Integration with Paynocchio failed. Response is <strong>%s</strong> " ), $json_response->message) ."</p></div>";
        }
    }

}