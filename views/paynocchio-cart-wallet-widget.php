<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
    global $woocommerce;
    $amount = WC()->cart->cart_contents_total;
?>

<?php
$paynocchio_classes = '';
$paynocchio_classes .= get_option( 'woocommerce_paynocchio_settings')['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
$paynocchio_classes .= get_option( 'woocommerce_paynocchio_settings')['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';

$accent_color = '#3b82f6';
if (get_option( 'woocommerce_paynocchio_settings')['accent_color']) {
    $accent_color = get_option( 'woocommerce_paynocchio_settings')['accent_color'];
}

$accent_text_color = '#ffffff';
if (get_option( 'woocommerce_paynocchio_settings')['accent_text_color']) {
    $accent_text_color = get_option( 'woocommerce_paynocchio_settings')['accent_text_color'];
}
?>

<style>
    .paynocchio_widget_colored {
        background-color: <?php echo $accent_color; ?>!important;
        color: <?php echo $accent_text_color; ?>!important;
    }
</style>

<div class="paynocchio-cart-wallet-widget <?php echo $paynocchio_classes; ?>">
    <div class="cart">
        <a href="<?php echo wc_get_cart_url(); ?>" alt="Checkout" title="Checkout">
            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-flex-nowrap">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/cart.png' ?>" class="!cfps-h-[25px] cfps-w-auto cfps-mr-2"/>
                <?php if (!is_user_logged_in()) { ?>
                    <div>Your cart: <span class="cfps-font-semibold">$<?php echo $amount; ?></span></div>
                <?php } else { ?>
                    <div class="cfps-font-semibold">$<?php echo $amount; ?></div>
                <?php } ?>
            </div>
        </a>
    </div>

    <?php if (is_user_logged_in() && get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
        $paynocchio = new Woocommerce_Paynocchio();
        $wallet = $paynocchio->get_paynocchio_wallet_info();
        ?>
        <div class="wallet paynocchio-wallet <?php if ($wallet['status'] !== 'ACTIVE') { ?>cfps-disabled<?php } ?>"">
            <div class="balance">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/wallet.png' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
                <div class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet['balance'] ?? 0 ?></span></div>
                <a title="Add money" alt="Add money"
                   class="add_money_button"
                    id="show_mini_modal">+</a>
            </div>
            <div class="bonuses">
                <div>Bonus:</div>
                <div class="cfps-font-semibold"><span class="paynocchio-numbers paynocchio-bonus-value"><?php echo $wallet['bonuses'] ?? 0 ?></span></div>
            </div>
        </div>

        <div class="topup_mini_form <?php echo $paynocchio_classes; ?>">
            <div class="top_up_mini_form_wrapper">
                <div class="cfps-flex cfps-flex-row">
                    <div class="cfps-text-lg cfps-font-semibold cfps-whitespace-nowrap"> Add $</div>
                     <input type="number" class="cfps-border-0 cfps-p-0 cfps-rounded-lg cfps-text-lg cfps-font-semibold cfps-w-[100px] cfps-bg-transparent"
                       placeholder="0" value="" id="top_up_amount_mini_form" />
                </div>
                <button id="top_up_mini_form_button"
                        type="button"
                        class="paynocchio_widget_colored mini_form_top_up_button">
                    Top up
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
