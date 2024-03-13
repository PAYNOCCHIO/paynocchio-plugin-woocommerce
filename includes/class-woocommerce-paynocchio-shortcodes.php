<?php

/**
 * Class for rendering shortcodes
 */

class Woocommerce_Paynocchio_Shortcodes {

    static function paynocchio_activation_block($attr) {
        ob_start();
        require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-activation-block.php');
        return ob_get_clean();
    }

    static function paynocchio_registration_block($attr) {
        ob_start();
        require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-registration-block.php');
        return ob_get_clean();
    }

    static function paynocchio_account_page() {
        ob_start();
        require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-account-page.php');
        return ob_get_clean();
    }

    static function paynocchio_payment_widget() {
        ob_start();
        require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-payment-widget.php');
        return ob_get_clean();
    }

    static function paynocchio_modal_forms() {
        ob_start();
        require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-modal-forms.php');
        return ob_get_clean();
    }

    static function paynocchio_cart_wallet_widget() {
        ob_start();
        require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-cart-wallet-widget.php');
        return ob_get_clean();
    }
}