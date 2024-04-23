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
       <!-- <div class="cfps-text-lg cfps-mb-4 cfps-flex cfps-flex-row cfps-items-center cfps-gap-4">
            <img src="<?php /*echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/star.webp' */?>" class="!cfps-h-[25px] !cfps-ml-0"/>
            <div>Earn <strong><?php /*echo WC()->cart->cart_contents_total * 0.1; */?> Bonuses</strong> with this purchase!</div>
        </div>-->
        <section class="paynocchio <?php echo $paynocchio_classes; ?>">
            <div class="paynocchio-payment-block">
                <div class="paynocchio_tiles">
                    <div class="!cfps-max-w-full cfps-rounded-xl cfps-p-8 cfps-flex cfps-flex-row cfps-flex-wrap cfps-items-center
                    cfps-justify-between cfps-gap-8 cfps-bg-gradient-to-r cfps-from-gray-200 cfps-to-gray-300">
                        <h2 class="!cfps-text-[#515151] cfps-text-2xl cfps-font-bold">Your Wallet</h2>
                        <div class="cfps-flex cfps-flex-row cfps-gap-8">
                            <div>
                                <p>Balance:</p>
                                <p class="cfps-text-xl cfps-font-bold">$<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet['balance'] ?? 0; ?></span></p>
                            </div>
                            <div>
                                <p>Bonuses:</p>
                                <p class="cfps-text-xl cfps-font-bold"><span class="paynocchio-numbers paynocchio-bonus-value"><?php echo $wallet['bonuses'] ?? 0 ?></span></p>
                            </div>
                        </div>
                        <div class=""><a href="#" class="btn-blue paynocchio_button" data-modal=".topUpModal">Add money</a></div>
                    </div>
                </div>
                <?php if($wallet['bonuses']) {
                    if($wallet['bonuses'] < $cart_total) {
                        $max_bonus = $wallet['bonuses'];
                    } else {
                        $max_bonus = $cart_total;
                    }
                    ?>
                    <div class="paynocchio-conversion-rate cfps-flex cfps-flex-row cfps-flex-wrap cfps-items-center cfps-gap-4 cfps-justify-between">
                        <h3>
                            Apply available bonuses
                        </h3>

                        <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-4">
                            <input id="bonuses-input" type="range" min="0" max="<?php echo $max_bonus; ?>" step="1" value="0" class="styled-slider slider-progress" />
                            <?php
                            woocommerce_form_field( 'bonusesvalue', [
                                'type'        => 'number',
                                'id'          => 'bonuses-value',
                                'label'       => '',
                                'placeholder' => '',
                                'default'     => '',
                                'input_class' => ['short focus:!cfps-outline-none !cfps-mb-0'],
                            ] );
                            ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-mt-8 cfps-text-sm cfps-flex-wrap">
                    <a href="/paynocchio-account">Paynocchio Account</a>
                    <a href="<?php echo get_privacy_policy_url(); ?>">Privacy Policy</a>
                    <a href="<?php echo get_permalink( wc_terms_and_conditions_page_id() ); ?>">Terms and Conditions</a>
                </div>
            </div>
        </section>
    <?php }  ?>

<?php } ?>
<?php echo do_shortcode('[paynocchio_modal_forms]'); ?>