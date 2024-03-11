<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<div class="paynocchio-profile-block paynocchio-blue-badge">
    <div class="cfps-grid cfps-grid-cols-[1fr_100px_200px_35px] cfps-gap-x-6">
        <div>
            Kopybara.Pay
        </div>
        <div>
            $<span class="paynocchio-numbers paynocchio-balance-value"></span>
        </div>
        <div>
            <span class="paynocchio-numbers paynocchio-bonus-value"></span>
        </div>
        <a class="tab-switcher cfps-cursor-pointer" id="wallet_toggle">
            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_r.png' ?>" />
        </a>
    </div>
</div>