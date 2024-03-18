<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
    global $woocommerce;
    $amount = WC()->cart->cart_contents_total;
?>

<div class="paynocchio-cart-wallet-widget cfps-bg-slate-100 cfps-p-2 cfps-rounded-lg cfps-flex cfps-flex-row cfps-items-center cfps-relative">
    <div class="cart">
        <a href="<?php echo wc_get_checkout_url(); ?>" alt="Checkout" title="Checkout">
            <div class="cfps-flex cfps-flex-row cfps-items-center">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/cart.png' ?>" class="!cfps-h-[25px] cfps-w-auto cfps-mr-2"/>
                <?php if (!is_user_logged_in()) { ?>
                    <p>Your cart: <span class="cfps-font-semibold">$<?php echo $amount; ?></span></p>
                <?php } else { ?>
                    <p class="cfps-font-semibold">$<?php echo $amount; ?></p>
                <?php } ?>
            </div>
        </a>
    </div>

    <?php if (is_user_logged_in() && get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
        $paynocchio = new Woocommerce_Paynocchio();
        $wallet = $paynocchio->get_paynocchio_wallet_info();
        ?>
        <div class="wallet cfps-flex cfps-flex-row cfps-items-center cfps-pl-2 cfps-ml-2 cfps-border-l cfps-border-slate-300">
            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-pr-2 cfps-mr-2 cfps-border-r cfps-border-slate-300 cfps-gap-x-2">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/wallet.png' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
                <p class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet['balance'] ?></span></p>
                <a title="Add money" alt="Add money"
                   class="cfps-bg-slate-300 cfps-rounded-3xl cfps-w-6 cfps-h-6 cfps-leading-6 cfps-text-center cfps-block cfps-ml-2 cfps-cursor-pointer"
                    id="show_mini_modal">+</a>
            </div>
            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2">
                <p>Bonus:</p>
                <p class="cfps-font-semibold"><span class="paynocchio-numbers paynocchio-bonus-value"><?php echo $wallet['bonuses'] ?></span></p>
            </div>
        </div>

        <div class="topup_mini_form cfps-hidden cfps-absolute cfps-top-[110%] cfps-left-0 cfps-w-full">
            <div class="cfps-p-4 cfps-bg-white cfps-drop-shadow cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2 cfps-rounded-lg">
                <p class="cfps-text-xl cfps-font-semibold">$</p>
                <input type="number" class="cfps-border-0 cfps-rounded-lg cfps-text-xl cfps-font-semibold cfps-w-full" value="0" id="top_up_amount" />
                <button id="top_up_mini_form_button"
                        type="button"
                        class="cfps-border-0 cfps-rounded-lg cfps-p-2 cfps-bg-blue-500 cfps-text-white cfps-whitespace-nowrap">
                    Top up
                    <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <?php wp_nonce_field( 'paynocchio_ajax_top_up', 'ajax-top-up-nonce' ); ?>
            </div>
        </div>
    <?php } ?>
</div>
