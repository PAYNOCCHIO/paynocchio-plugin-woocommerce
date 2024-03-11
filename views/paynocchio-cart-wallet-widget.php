<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
    global $woocommerce;
    $amount = WC()->cart->cart_contents_total;
?>

<div class="paynocchio-cart-wallet-widget cfps-bg-slate-100 cfps-p-2 cfps-rounded-lg cfps-flex cfps-flex-row cfps-items-center">
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

    <div class="wallet cfps-flex cfps-flex-row cfps-items-center">
        <div class="cfps-flex cfps-flex-row cfps-items-center cfps-pr-2 cfps-mr-2 cfps-border-r cfps-border-slate-300 cfps-gap-x-2">
            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/wallet.png' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
            <p class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value"></span></p>
            <a href="#" title="Add money" alt="Add money" class="cfps-bg-slate-300 cfps-rounded-3xl cfps-w-6 cfps-h-6 cfps-leading-6 cfps-text-center cfps-block cfps-ml-2">+</a>
        </div>
        <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2">
            <p>Bonus:</p>
            <p class="cfps-font-semibold"><span class="paynocchio-numbers paynocchio-bonus-value"></span></p>
        </div>
    </div>
</div>
