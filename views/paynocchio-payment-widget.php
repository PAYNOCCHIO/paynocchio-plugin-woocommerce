<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php

global $woocommerce;
$cart = $woocommerce->cart;
$cart_total = floatval($woocommerce->cart->total);

if (is_user_logged_in()) {
    $paynocchio = new Woocommerce_Paynocchio();
    $wallet = $paynocchio->get_paynocchio_wallet_info();
    ?>

    <?php if ($wallet['status'] !== 'ACTIVE') { ?>
        <div>
            <p>Current Wallet is <?php echo $wallet['status'] ?></p>
            <?php if ($wallet['status'] !== 'BLOCKED') { ?>
                <p>You can manage it at your wallet <a href="/<?php echo WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG ?>">account page</a>.</p>
            <?php } ?>
        </div>

    <?php } else { ?>

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
                        <p>$<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet['balance'] ?? 0; ?></span></p>
                    </div>
                    <div>
                        <p>Bonuses</p>
                        <p><span class="paynocchio-numbers paynocchio-bonus-value"><?php echo $wallet['bonuses'] ?? 0 ?></span></p>
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
        <?php if($wallet['bonuses']) {
             if($wallet['bonuses'] < $cart_total) {
                 $max_bonus = $wallet['bonuses'];
             } else {
                 $max_bonus = $cart_total;
             }
            ?>
        <div class="paynocchio-conversion-rate cfps-mt-8">
            <h3>
                How much do you want to pay in bonuses?
            </h3>
            <?php
            woocommerce_form_field( 'bonusesvalue', [
                'type'        => 'number',
                'id'          => 'bonuses-value',
                'label'       => '',
                'placeholder' => '',
                'default'     => '',
                'input_class' => ['short'],
            ] );
            ?>
            <input id="bonuses-input" type="range" min="0" max="<?php echo $max_bonus; ?>" step="1" value="0" class="styled-slider slider-progress" />

        </div>
    <?php } ?>

        <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-mt-8">
            <a href="#">Manage Cards</a>
            <a href="#">History</a>
            <a href="#">Support</a>
            <a href="#">Terms</a>
        </div>
    </div>
</section>
    <?php }  ?>

<?php } ?>
<?php echo do_shortcode('[paynocchio_modal_forms]'); ?>