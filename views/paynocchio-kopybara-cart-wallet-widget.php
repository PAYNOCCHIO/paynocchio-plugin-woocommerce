<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
global $woocommerce;
$amount = WC()->cart->cart_contents_total;
?>

<div class="paynocchio-cart-wallet-widget cfps-flex cfps-flex-row cfps-items-center cfps-relative cfps-text-black">
    <div class="cart cfps-bg-white cfps-rounded-lg cfps-p-2">
        <a href="<?php echo wc_get_page_permalink('myaccount') . WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG ?>" alt="Account" title="Account">
            <div class="cfps-flex cfps-flex-row cfps-items-center">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/profile_black.png' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
            </div>
        </a>
    </div>

    <?php if (is_user_logged_in() && get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
        $paynocchio = new Woocommerce_Paynocchio();
        $wallet = $paynocchio->get_paynocchio_wallet_info();
        ?>
        <div class="wallet paynocchio-wallet <?php if ($wallet['status'] !== 'ACTIVE') { ?>cfps-disabled<?php } ?> cfps-bg-white cfps-rounded-lg cfps-p-2"  alt="Balance" title="Balance">
            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-pr-2 cfps-mr-2 cfps-border-r cfps-border-slate-300 cfps-gap-x-2">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/wallet.svg' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
                <p class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value" data-balance="<?php echo $wallet['balance'] ?? 0 ?>"><?php echo $wallet['balance'] ?? 0 ?></span></p>
                <a title="Add money" alt="Add money"
                   class="cfps-bg-slate-300 cfps-rounded-3xl cfps-w-6 cfps-h-6 cfps-leading-6 cfps-text-center cfps-block cfps-ml-2 cfps-cursor-pointer"
                   id="show_mini_modal">+</a>
            </div>
            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2" alt="Bonuses" title="Bonuses">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/bonuses.svg' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
                <!--<p>Bonus:</p>-->
                <p class="cfps-font-semibold"><span class="paynocchio-numbers paynocchio-bonus-value" data-bonus="<?php echo $wallet['bonuses'] ?? 0 ?>"><?php echo $wallet['bonuses'] ?? 0 ?></span></p>
            </div>
        </div>

        <div class="topup_mini_form cfps-hidden cfps-absolute cfps-top-[110%] cfps-min-w-full">
            <div class="cfps-p-2 cfps-bg-white cfps-drop-shadow cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2 cfps-rounded-lg cfps-justify-between">
                <div class="cfps-flex cfps-flex-row">
                    <p class="cfps-text-xl cfps-font-semibold cfps-whitespace-nowrap"> Add $</p>
                    <input type="number" class="cfps-border-0 cfps-p-0 cfps-rounded-lg cfps-text-xl cfps-font-semibold cfps-w-[100px]"
                           placeholder="0" value="" id="top_up_amount_mini_form" />
                </div>
                <button id="top_up_mini_form_button"
                        type="button"
                        class="cfps-border-0 cfps-rounded-lg cfps-p-2 cfps-bg-blue-500 cfps-text-white cfps-flex cfps-flex-row">
                    <div class="cfps-whitespace-nowrap">Top up</div>
                    <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg class="cfps-check cfps-hidden cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white cfps-fill-white" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"></path>
                    </svg>
                    <svg class="cfps-cross cfps-hidden cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white cfps-fill-white" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"></path>
                    </svg>
                </button>
                <?php wp_nonce_field( 'paynocchio_ajax_top_up', 'ajax-top-up-nonce-mini-form' ); ?>
            </div>

        </div>
    <?php } ?>
</div>
