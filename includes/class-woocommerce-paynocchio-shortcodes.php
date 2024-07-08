<?php

/**
 * Class for rendering shortcodes
 */

class Woocommerce_Paynocchio_Shortcodes {

    static function paynocchio_activation_block($attr) {
        $plugin = new Woocommerce_Paynocchio();

        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-kopybara-activation-block.php');
        } else {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-unapproved.php');
        }
        return ob_get_clean();
    }

    static function paynocchio_registration_block($attr) {
        ob_start();
        require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-kopybara-registration-block.php');
        return ob_get_clean();
    }

    static function paynocchio_account_page() {
        $plugin = new Woocommerce_Paynocchio();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-kopybara-account-page.php');
        } else {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-unapproved.php');
        }
        return ob_get_clean();
    }

    static function paynocchio_payment_widget() {
        $plugin = new Woocommerce_Paynocchio();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-kopybara-payment-widget.php');
        } else {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-unapproved.php');
        }
        return ob_get_clean();
    }

    static function paynocchio_modal_forms() {
        $plugin = new Woocommerce_Paynocchio();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-modal-forms.php');
        } else {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-unapproved.php');
        }
        return ob_get_clean();
    }

    static function paynocchio_cart_wallet_widget() {
        $plugin = new Woocommerce_Paynocchio();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-cart-wallet-widget.php');
        } else {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-unapproved.php');
        }
        return ob_get_clean();
    }

    static function paynocchio_kopybara_cart_wallet_widget() {
        $plugin = new Woocommerce_Paynocchio();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-kopybara-cart-wallet-widget.php');
        } else {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-unapproved.php');
        }
        return ob_get_clean();
    }

    static function paynocchio_wallet_management_page () {
        $plugin = new Woocommerce_Paynocchio();
        ob_start();
        if($plugin->is_approved()) {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-wallet-management-page.php');
        } else {
            require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-unapproved.php');
        }
        return ob_get_clean();
    }
}