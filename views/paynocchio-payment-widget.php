<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
$wallet_balance = 0;
$wallet_bonus = 0;
$wallet_pan = '';

global $woocommerce;
$cart = $woocommerce->cart;
$cart_total = intval($woocommerce->cart->total);

if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $user_paynocchio_wallet_id = get_user_meta($current_user->ID, 'paynoccio_wallet', true);
    if($user_paynocchio_wallet_id) {
        $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($current_user->ID);
        $wallet_bal_bon = $user_paynocchio_wallet->getWalletBalance($user_paynocchio_wallet_id);
        if($wallet_bal_bon) {
            $wallet_balance = $wallet_bal_bon['balance'];
            $wallet_bonus = $wallet_bal_bon['bonuses'];
            $wallet_pan = $wallet_bal_bon['number'];
        }
    }
};
?>

<section class="paynocchio">
    <div class="paynocchio-embleme">
        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/kopybara-logo.png' ?>" class="!cfps-block !cfps-mx-auto" />
    </div>
    <div class="paynocchio-payment-block">
        <div class="cfps-grid cfps-grid-cols-[1fr_1fr] cfps-gap-x-8 cfps-items-stretch">
            <div class="paynocchio-card-simulator">
                <h3 class="!cfps-mb-0">Kopybara.Pay</h3>
                <div class="cfps-flex cfps-flex-row cfps-items-center cfps-text-white cfps-gap-x-8 cfps-text-xl">
                    <div>
                        <p>Balance</p>
                        <p>$<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet_balance; ?></span></p>
                    </div>
                    <div>
                        <p>Bonuses</p>
                        <p><span class="paynocchio-numbers paynocchio-bonus-value"><?php echo $wallet_bonus ?></span></p>
                    </div>
                </div>

                <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2">
                    <a href="#" class="btn-blue" data-modal=".topUpModal">Add money</a>
                    <a href="#" class="btn-white" data-modal=".withdrawModal">Withdraw</a>
                </div>
            </div>
            <div class="paynocchio-promo-badge">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/cashback_ill.png' ?>" class="cfps-max-h-full cfps-float-right" />
                <h3 class="!cfps-mb-0">Ultimate Cashback</h3>
                <p>Make three purchases and get an increased cashback on everything!</p>
                <a class="btn-white cfps-absolute cfps-bottom-4 cfps-left-4" href="#">Read more</a>
            </div>
        </div>
        <?php if($wallet_bonus) {
             if($wallet_bonus < $cart_total) {
                 $max_bonus = $wallet_bonus;
             } else {
                 $max_bonus = $cart_total;
             }
            ?>
        <div class="paynocchio-conversion-rate cfps-my-8">
            <h3>
                How much do you want to pay in bonuses?
            </h3>
            <?php
            woocommerce_form_field( 'bonuses_value', [
                'type'        => 'number',
                'id'          => 'bonuses-value',
                'label'       => '',
                'placeholder' => '',
                'default'     => '',
                'class'       => ['cfps-bg-white cfps-w-[50px] cfps-border-0 cfps-p-0 !cfps-mb-6 cfps-text-xl cfps-block'],
                'input_class' => ['short'],
            ] );
            ?>
                <input id="bonuses-input" type="range" min="0" max="<?php echo $max_bonus; ?>" step="1" value="0" />

        </div>
    <?php } ?>

        <div class="cfps-flex cfps-flex-row cfps-gap-x-4">
            <a href="#">Manage Cards</a>
            <a href="#">History</a>
            <a href="#">Support</a>
            <a href="#">Terms</a>
        </div>
    </div>
</section>

<?php echo do_shortcode('[paynocchio_modal_forms]'); ?>