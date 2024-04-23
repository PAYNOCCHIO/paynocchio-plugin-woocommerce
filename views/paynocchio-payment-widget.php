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

<?php
    $paynocchio_classes = '';
    $settigns = get_option( 'woocommerce_paynocchio_settings');
    $paynocchio_classes .= array_key_exists('darkmode', $settigns) && $settigns['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
    $paynocchio_classes .= array_key_exists('rounded', $settigns) && $settigns['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';
    $embleme_link = plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/paynocchio_';
    $embleme_link .= array_key_exists('darkmode', $settigns) && $settigns['darkmode'] == 'yes' ? 'white.svg' : 'black.svg';

    $accent_color = '#3b82f6';
    if (array_key_exists('accent_color', $settigns)) {
        $accent_color = get_option( 'woocommerce_paynocchio_settings')['accent_color'];
    }

    $accent_text_color = '#ffffff';
    if (array_key_exists('accent_text_color', $settigns)) {
        $accent_text_color = get_option( 'woocommerce_paynocchio_settings')['accent_text_color'];
    }
?>

<style>
    .paynocchio_colored {
        background-color: <?php echo $accent_color; ?>!important;
        color: <?php echo $accent_text_color; ?>!important;
    }
</style>

<?php if ($wallet['status'] !== 'ACTIVE') { ?>
    <div>
        <p>Current Wallet is <?php echo $wallet['status'] ?></p>
        <?php if ($wallet['status'] !== 'BLOCKED') { ?>
            <p>You can manage it at your wallet <a href="/<?php echo WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG ?>">account page</a>.</p>
        <?php } ?>
    </div>

<?php } else { ?>

<section class="paynocchio <?php echo $paynocchio_classes; ?>">
    <div class="paynocchio-payment-block">
        <div class="paynocchio-embleme">
            <img src="<?php echo $embleme_link; ?>" />
        </div>
        <div class="paynocchio_tiles">
            <div class="paynocchio-card-simulator">
                <h3>Your Wallet</h3>
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

                <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-2 cfps-flex-wrap">
                    <a href="#" class="btn-blue paynocchio_button" data-modal=".topUpModal">Add money</a>
                    <a href="#" class="btn-white paynocchio_button" data-modal=".withdrawModal">Withdraw</a>
                </div>
            </div>
            <div class="paynocchio-promo-badge">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/cashback_ill.png' ?>"
                     class="!cfps-h-[100px] !cfps-max-h-[100%] !cfps-float-right hidden lg:block" />
                <h3>Ultimate Cashback</h3>
                <p>Make three purchases and get an increased cashback on everything!</p>
                <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2 cfps-mt-4">
                    <a class="btn-white paynocchio_button" href="#">Read more</a>
                </div>
            </div>
        </div>
        <?php if($wallet['bonuses']) {
             if($wallet['bonuses'] < $cart_total) {
                 $max_bonus = $wallet['bonuses'];
             } else {
                 $max_bonus = $cart_total;
             }
            ?>
        <div class="paynocchio-conversion-rate">
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
                'input_class' => ['short focus:!cfps-outline-none'],
            ] );
            ?>
            <input id="bonuses-input" type="range" min="0" max="<?php echo $max_bonus; ?>" step="1" value="0" class="styled-slider slider-progress" />
        </div>
    <?php } ?>
        <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-mt-8 cfps-text-sm cfps-flex-wrap">
            <a href="/paynocchio-account">Paynocchio Account</a>
            <a href="<?php echo get_privacy_policy_url(); ?>">Privacy Policy</a>
            <a href="<?php echo get_permalink( wc_terms_and_conditions_page_id() ); ?>">Terms and Conditions</a>
        </div>
        <div class="checkout_pricing">
            <div class="price">
                <h4>Total:</h4>
                <h2><?php wc_cart_totals_order_total_html(); ?></h2>
            </div>
            <div class="bonus">
                <h4>You earning for this purchase:</h4>
                <h2>+146 bonuses</h2>
            </div>
        </div>
    </div>
</section>
    <?php }  ?>

<?php } ?>
<?php echo do_shortcode('[paynocchio_modal_forms]'); ?>