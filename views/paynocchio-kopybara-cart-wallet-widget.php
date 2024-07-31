<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
    global $woocommerce;
    $amount = WC()->cart->cart_contents_total;

    $paynocchio_classes = '';
    $settings = get_option( 'woocommerce_paynocchio_settings');
    if($settings) {
        $paynocchio_classes .= array_key_exists('darkmode', $settings) && $settings['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
        $paynocchio_classes .= array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';
    }
?>

<div class="paynocchio-cart-wallet-widget cfps-flex cfps-flex-row cfps-items-center cfps-relative cfps-text-black <?php echo $paynocchio_classes; ?>">
    <div class="cart">
        <a href="<?php echo wc_get_cart_url(); ?>" alt="Checkout" title="Checkout">
            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-flex-nowrap">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/cart.svg' ?>" class="!cfps-h-[25px] cfps-w-auto cfps-mr-2"/>
                <?php if (!is_user_logged_in()) { ?>
                    <div>Your order: <span class="cfps-font-semibold">$<?php echo $amount; ?></span></div>
                <?php } else { ?>
                    <div class="cfps-font-semibold">$<?php echo $amount; ?></div>
                <?php } ?>
            </div>
        </a>
    </div>

    <?php if (is_user_logged_in()) { ?>
        <div class="cart cfps-bg-white cfps-rounded-lg cfps-p-2">
            <a href="<?php echo wc_get_page_permalink('myaccount') . WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG ?>" alt="Account" title="Account">
                <div class="cfps-flex cfps-flex-row cfps-items-center">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/profile.svg' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
                </div>
            </a>
        </div>
    <?php } else { ?>
        <div class="cart !cfps-bg-blue-500 !cfps-text-white cfps-rounded-lg cfps-p-2">
            <a href="<?php echo wc_get_page_permalink('myaccount') . WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG ?>" alt="Account" title="Account">
                <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-2">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/profile-white.svg' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
                    <div class="!cfps-text-white !cfps-font-normal">Log In</div>
                </div>
            </a>
        </div>
    <?php } ?>

    <?php if (is_user_logged_in() && get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) {
        $paynocchio = new Woocommerce_Paynocchio();
        $wallet = $paynocchio->get_paynocchio_wallet_info();
        $wallet_balance = round($wallet['balance'], 2);
        $wallet_bonuses = $wallet['bonuses'];
        ?>

    <?php if ($wallet['code'] !== 500) { ?>
        <div class="wallet paynocchio-wallet <?php if ($wallet['status'] !== 'ACTIVE') { ?>cfps-disabled<?php } ?> cfps-p-2"  alt="Balance" title="Balance">
            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-pr-2 cfps-mr-2 cfps-border-r cfps-border-slate-300 cfps-gap-x-2">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/wallet.svg' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
                <p class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value" data-balance="<?php echo $wallet_balance ?? 0 ?>"><?php echo $wallet_balance ?? 0 ?></span></p>
            </div>
            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2" alt="Bonuses" title="Bonuses">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/bonuses.svg' ?>" class="!cfps-h-[25px] cfps-w-auto"/>
                <p class="cfps-font-semibold"><span class="paynocchio-numbers paynocchio-bonus-value" data-bonus="<?php echo $wallet_bonuses ?? 0 ?>"><?php echo $wallet_bonuses ?? 0 ?></span></p>
            </div>
        </div>
        <?php } else {?>
            <div class="wallet paynocchio-wallet cfps-gap-2">Error. Please contact <strong><?php echo get_bloginfo('name') ?></strong> support</div>
        <?php } ?>
    <?php } ?>
</div>
