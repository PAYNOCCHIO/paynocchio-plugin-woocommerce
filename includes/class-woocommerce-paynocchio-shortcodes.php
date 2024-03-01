<?php

/**
 * Class for rendering shortcodes
 */

class Woocommerce_Paynocchio_Shortcodes {

    static function paynocchio_activation_block() {
        ob_start();
        require(WOOCOMMERCE_PAYNOCCHIO_PATH . 'views/paynocchio-activation-page-content.php');
        return ob_get_clean();
    }
}