<?php

/**
 * The file that adds Block editor support for Paynocchio Gateway
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cfps.co
 * @since      1.0.0
 *
 * @package    Woocommerce_Paynocchio
 * @subpackage Woocommerce_Paynocchio/includes
 */

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

final class Woocommerce_Paynocchio_Gateway_Blocks_Support extends AbstractPaymentMethodType {

    private $gateway;

    protected $name = 'paynocchio'; // payment gateway id

    public function initialize() {
        // get payment gateway settings
        $this->settings = get_option( "woocommerce_{$this->name}_settings", array() );

        // you can also initialize your payment gateway here
        // $gateways = WC()->payment_gateways->payment_gateways();
        // $this->gateway  = $gateways[ $this->name ];
    }

    public function is_active() {
        return ! empty( $this->settings[ 'enabled' ] ) && 'yes' === $this->settings[ 'enabled' ];
    }

    public function get_payment_method_script_handles() {

        wp_register_script(
            'paynocchio-blocks-integration',
            plugin_dir_url( __DIR__ ) . 'build/index.js',
            array(
                'wc-blocks-registry',
                'wc-settings',
                'wp-element',
                'wp-html-entities',
            ),
            null, // or time() or filemtime( ... ) to skip caching
            true
        );

        return array( 'paynocchio-blocks-integration' );

    }

    public function get_payment_method_data() {
        return array(
            'title'        => $this->get_setting( 'title' ),
            // almost the same way:
            // 'title'     => isset( $this->settings[ 'title' ] ) ? $this->settings[ 'title' ] : 'Default value';
            'description'  => $this->get_setting( 'description' ),
            // if $this->gateway was initialized on line 15
            // 'supports'  => array_filter( $this->gateway->supports, [ $this->gateway, 'supports' ] ),

            // example of getting a public key
            // 'publicKey' => $this->get_publishable_key(),
        );
    }

}