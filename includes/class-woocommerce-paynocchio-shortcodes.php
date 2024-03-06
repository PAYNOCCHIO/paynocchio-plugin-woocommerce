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
}