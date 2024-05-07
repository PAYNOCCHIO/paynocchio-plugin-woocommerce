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
    $paynocchio_rounded_class = array_key_exists('rounded', $settigns) && $settigns['rounded'] == 'yes' ? 'cfps-rounded-lg' : '';
    $paynocchio_embleme_url = array_key_exists('embleme_url', $settigns) && $settigns['embleme_url'] ? $settigns['embleme_url'] : '';

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

   <?php if($wallet['code'] !== 500) { ?>
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

                    <div class="paynocchio-card-container">
                        <div class="paynocchio-card" style="color:<?php echo $accent_text_color; ?>; background:<?php echo $accent_color; ?>">
                            <?php if ($paynocchio_embleme_url) { ?>
                                <img src="<?php echo $paynocchio_embleme_url; ?>" class="on_card_embleme" />
                            <?php } ?>
                            <?php
                            if ($wallet['status'] == 'SUSPEND') {
                                echo '<div class="wallet_status">SUSPENDED</div>';
                            } elseif ($wallet['status'] == 'BLOCKED') {
                                echo '<div class="wallet_status">BLOCKED</div>';
                            }
                            ?>
                            <div class="paynocchio-balance-bonuses">
                                <div class="paynocchio-balance">
                                    <div>
                                        Balance
                                    </div>
                                    <div class="amount">
                                        $<span class="paynocchio-numbers paynocchio-balance-value" data-balance="<?php echo $wallet['balance'] ?>"><?php echo $wallet['balance'] ?></span>
                                    </div>
                                </div>
                                <div class="paynocchio-bonuses">
                                    <div>
                                        Bonuses
                                    </div>
                                    <div class="amount">
                                        <span class="paynocchio-numbers paynocchio-bonus-value" data-bonus="<?php echo $wallet['bonuses'] ?>"><?php echo $wallet['bonuses'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="paynocchio-card-number">
                                <div><?php echo chunk_split(strval($wallet['card_number']), 4, '</div><div>'); ?></div>
                            </div>
                            <div class="paynocchio_payment_card_button">
                                <a href="#" class="paynocchio_button cfps-flex cfps-flex-row cfps-items-center" data-modal=".topUpModal" style="color:<?php echo $accent_color; ?>; background:<?php echo $accent_text_color; ?>">
                                    <svg class="cfps-h-[20px] cfps-w-[20px] cfps-mr-1 cfps-inline-block" enable-background="new 0 0 50 50" version="1.1" viewBox="0 0 50 50" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <rect fill="none" height="50" width="50"/>
                                        <line fill="none" stroke="#1a85f9" stroke-miterlimit="10" stroke-width="4" x1="9" x2="41" y1="25" y2="25"/>
                                        <line fill="none" stroke="#1a85f9" stroke-miterlimit="10" stroke-width="4" x1="25" x2="25" y1="9" y2="41"/>
                                    </svg>
                                    Add money
                                </a>
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
                        <a href="<?php echo wc_get_page_permalink('myaccount') . WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG ?>">My Account</a>
                        <a href="<?php echo get_privacy_policy_url(); ?>">Privacy Policy</a>
                        <a href="<?php echo get_permalink( wc_terms_and_conditions_page_id() ); ?>">Terms and Conditions</a>
                    </div>
                </div>
            </section>
        <?php }  ?>
        <?php } else { ?>
        <div class="paynocchio_error_notification <?php echo $paynocchio_rounded_class; ?>">
            <svg class="cfps-max-w-[100px] cfps-mx-auto cfps-mb-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0h24v24H0z" fill="none"></path>
                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm0-8v6h2V7h-2z" fill="#d4d4d4" class="fill-000000"></path>
            </svg>
            <div class="cfps-mb-4">
                <b><?php echo get_bloginfo('name') ?></b> wallet system is currently unavailable
            </div>
            <div>To solve the problem, please contact <b><?php echo get_bloginfo('name') ?></b> support</div>
        </div>
        <?php } ?>

<?php } ?>
<?php echo do_shortcode('[paynocchio_modal_forms]'); ?>