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
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_Error( 'no_user', 'Invalid user', array( 'status' => 404 ) );
    }
    $user_uuid = get_user_meta($user_id, PAYNOCCHIO_USER_UUID_KEY, true);
    $wallet_uuid = get_user_meta($user_id, PAYNOCCHIO_WALLET_KEY, true);

    $paynocchio = new Woocommerce_Paynocchio();
    $wallet_info = $paynocchio->get_paynocchio_wallet_info();

    $wallet_structure = $wallet_info['structure'];
    $wallet_balance = $wallet_info['balance'];
    $wallet_bonuses = $wallet_info['bonuses'];
    $minimum_topup_amount = (float) $wallet_structure['minimum_topup_amount'];
    $card_balance_limit = (float) $wallet_structure['card_balance_limit'];
    $rewarding_rules = $wallet_structure['rewarding_group']->rewarding_rules;
    $bonuses_conversion_rate = (float) $wallet_structure['bonus_conversion_rate'];

    echo '<pre>';
    print_r($wallet_info);
    print_r($user_uuid);
    echo '<br />';
    print_r($wallet_uuid);
    echo '</pre>';
?>

<?php
    $paynocchio_classes = '';
    $accent_color = '#3b82f6';
    $accent_text_color = '#ffffff';

    $settings = get_option( 'woocommerce_paynocchio_settings');
    if ($settings) {
        $paynocchio_classes .= array_key_exists('darkmode', $settings) && $settings['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
        $paynocchio_classes .= array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';
        $paynocchio_rounded_class = array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'cfps-rounded-lg' : '';
        $paynocchio_embleme_url = array_key_exists('embleme_url', $settings) && $settings['embleme_url'] ? $settings['embleme_url'] : '';

        if (array_key_exists('accent_color', $settings)) {
            $accent_color = $settings['accent_color'];
        }
        if (array_key_exists('accent_text_color', $settings)) {
            $accent_text_color = $settings['accent_text_color'];
        }
    }
?>

    <style>
        .paynocchio_colored {
            background-color: <?php echo $accent_color; ?>!important;
            color: <?php echo $accent_text_color; ?>!important;
        }
    </style>

   <?php if($wallet_info['code'] !== 500) { ?>
        <?php if ($wallet_info['status'] != 'ACTIVE') { ?>
            <div>
                <p>Current Wallet is <?php echo $wallet_info['status'] ?></p>
                <?php if ($wallet_info['status'] != 'BLOCKED') { ?>
                    <p>You can manage it at your wallet <a href="/<?php echo WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG ?>">account page</a>.</p>
                <?php } ?>
            </div>

        <?php } else { ?>

            <section class="paynocchio <?php echo $paynocchio_classes; ?>">
                <div class="paynocchio-payment-block">
                    <?php if(get_transient('first_time_active')) { ?>
                        <div class="congratz !cfps-justify-center">
                            <div>Congratulations! Your Wallet has been activated!</div>
                        </div>
                    <?php } ?>
                    <div class="paynocchio-card-bonuses">
                        <div class="paynocchio-card-container">
                            <div class="paynocchio-card" style="color:<?php echo $accent_text_color; ?>; background:<?php echo $accent_color; ?>">
                                <?php if ($paynocchio_embleme_url) { ?>
                                    <img src="<?php echo $paynocchio_embleme_url; ?>" class="on_card_embleme" />
                                <?php } ?>
                                <?php
                                if ($wallet_info['status'] == 'SUSPEND') {
                                    echo '<div class="wallet_status">SUSPENDED</div>';
                                } elseif ($wallet_info['status'] == 'BLOCKED') {
                                    echo '<div class="wallet_status">BLOCKED</div>';
                                }
                                ?>
                                <div class="paynocchio-balance-bonuses">
                                    <div class="paynocchio-balance">
                                        <div>
                                            Balance
                                        </div>
                                        <div class="amount">
                                            $<span class="paynocchio-numbers paynocchio-balance-value" data-balance="<?php echo $wallet_info['balance'] ?>"><?php echo $wallet_info['balance'] ?></span>
                                        </div>
                                    </div>
                                    <div class="paynocchio-bonuses">
                                        <div>
                                            Bonuses
                                        </div>
                                        <div class="amount">
                                            <span class="paynocchio-numbers paynocchio-bonus-value" data-bonus="<?php echo $wallet_info['bonuses'] ?>"><?php echo $wallet_info['bonuses'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="paynocchio-card-number">
                                    <div><?php echo chunk_split(strval($wallet_info['card_number']), 4, '</div><div>'); ?></div>
                                </div>
                            </div>
                            <div class="paynocchio_payment_card_button">
                                <a href="#" class="paynocchio_button cfps-flex cfps-flex-row cfps-items-center" data-modal=".topUpModal" style="background:<?php echo $accent_color; ?>; color:<?php echo $accent_text_color; ?>">
                                    <svg class="cfps-h-[20px] cfps-w-[20px] cfps-mr-1 cfps-inline-block" enable-background="new 0 0 50 50" version="1.1" viewBox="0 0 50 50" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <rect fill="none" height="50" width="50"/>
                                        <line fill="none" stroke="<?php echo $accent_text_color; ?>" stroke-miterlimit="10" stroke-width="4" x1="9" x2="41" y1="25" y2="25"/>
                                        <line fill="none" stroke="<?php echo $accent_text_color; ?>" stroke-miterlimit="10" stroke-width="4" x1="25" x2="25" y1="9" y2="41"/>
                                    </svg>
                                    Add money
                                </a>
                            </div>
                        </div>
                        <?php
                        if($wallet_info['bonuses']) {
                            if($wallet_info['bonuses'] * $bonuses_conversion_rate < $cart_total) {
                                $max_bonuses = $wallet_info['bonuses'];
                                $max_bonuses_in_money = $wallet_info['bonuses'] * $bonuses_conversion_rate;
                            } else {
                                $max_bonuses = $cart_total / $bonuses_conversion_rate;
                                $max_bonuses_in_money = $cart_total;
                            }

                            $discount = round($max_bonuses * $bonuses_conversion_rate * 100 / $cart_total, 2);
                            $new_price = $cart_total - $max_bonuses_in_money;
                            ?>
                            <div class="paynocchio-conversion-rate">
                                <div class="paynocchio-cart-total">
                                    <p><strong>Your order total:</strong></p>
                                    <div class="paynocchio-cart-amounts">
                                        <h3>$<span id="paynocchio_after_discount"><?php echo $new_price; ?></span></h3>
                                        <h3 class="paynocchio_before_discount">
                                            $<span id="paynocchio_before_discount"><?php echo $cart_total; ?></span>
                                        </h3>
                                        <h3 class="paynocchio_discount">
                                            -<span id="paynocchio_discount"><?php echo $discount; ?></span>%
                                        </h3>
                                    </div>
                                </div>

                                <div>
                                    <h3>
                                        Apply available bonuses
                                    </h3>
                                    <div>Your order will be partially covered by bonuses.</div>
                                </div>

                                <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-4">
                                    <input id="bonuses-input" type="range" min="0" max="<?php echo $max_bonuses; ?>" step="1" value="<?php echo $max_bonuses; ?>" class="styled-slider slider-progress" />
                                    <?php
                                    woocommerce_form_field( 'bonusesvalue', [
                                        'type'        => 'number',
                                        'id'          => 'bonuses-value',
                                        'label'       => '',
                                        'placeholder' => '',
                                        'default'     => '',
                                        'disabled' => 'disabled',
                                        'input_class' => ['short focus:!cfps-outline-none !cfps-mb-0'],
                                        'value'       => $max_bonuses,
                                    ] );
                                    ?>
                                </div>
                                <p class="paynocchio_payment_bonuses">You will get <span id="paynocchio_payment_bonuses"></span> additional bonuses for this purchase.</p>
                            </div>
                        <?php } ?>
                    </div>
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
            <svg class="cfps-max-w-24 cfps-mx-auto cfps-mb-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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