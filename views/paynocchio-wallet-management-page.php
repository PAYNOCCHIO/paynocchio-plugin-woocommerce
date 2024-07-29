<?php

    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_Error( 'no_user', 'Invalid user', array( 'status' => 404 ) );
    }
    $user_uuid = get_user_meta($user_id, PAYNOCCHIO_USER_UUID_KEY, true);
    $wallet_uuid = get_user_meta($user_id, PAYNOCCHIO_WALLET_KEY, true);

    $paynocchio = new Woocommerce_Paynocchio();
    $wallet_info = $paynocchio->get_paynocchio_wallet_info();



    $wallet_structure = $wallet_info['structure'];
    $wallet_balance = round($wallet_info['balance'], 2);
    $wallet_bonuses = $wallet_info['bonuses'];
    $minimum_topup_amount = $wallet_structure['minimum_topup_amount'];
    $card_balance_limit = $wallet_structure['card_balance_limit'];
    $rewarding_rules = $wallet_structure['rewarding_group']->rewarding_rules;
    $allow_withdrawal = $wallet_structure['allow_withdraw'];

    /*echo '<pre>';
    print_r($wallet_info);
    echo '<br />';
    //print_r($rewarding_rules);
    echo '<br />';
    print_r($user_uuid);
    echo '<br />';
    print_r($wallet_uuid);
    echo '<br />';
    print_r($allow_withdrawal);
    echo '</pre>';*/

    $paynocchio_classes = '';
    $accent_color = '#3b82f6';
    $accent_text_color = '#ffffff';

    $settings = get_option( 'woocommerce_paynocchio_settings');

    if($settings) {
        $paynocchio_classes .= array_key_exists('darkmode', $settings) && $settings['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
        $paynocchio_classes .= array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';
        $paynocchio_rounded_class = array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'cfps-rounded-lg' : '';
        $paynocchio_darkmode_class = array_key_exists('rounded', $settings) && $settings['darkmode'] == 'yes' ? 'paynocchio_dark_mode' : '';
        $paynocchio_embleme_url = array_key_exists('embleme_url', $settings) && $settings['embleme_url'] ? $settings['embleme_url'] : '';

        if (array_key_exists('accent_color', $settings)) {
            $accent_color = get_option( 'woocommerce_paynocchio_settings')['accent_color'];
        }

        if (array_key_exists('accent_text_color', $settings)) {
            $accent_text_color = get_option( 'woocommerce_paynocchio_settings')['accent_text_color'];
        }
    }

    echo do_shortcode('[paynocchio_modal_forms]');
?>

    <style>
        .paynocchio_colored {
            background-color: <?php echo $accent_color; ?>!important;
            color: <?php echo $accent_text_color; ?>!important;
        }
    </style>

<?php if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY, true)) { ?>
    <div class="paynocchio-profile-block <?php echo $paynocchio_rounded_class; ?> <?php echo $paynocchio_darkmode_class; ?>">
        <?php echo do_shortcode('[paynocchio_activation_block]'); ?>
    </div>
<?php } else { ?>
    <?php if ($wallet_info['code'] !== 500) { ?>
        <div class="paynocchio-profile-block <?php echo $paynocchio_rounded_class; ?> <?php echo $paynocchio_darkmode_class; ?> <?php if ($wallet_info['status'] !== 'ACTIVE') { ?>cfps-disabled<?php } ?>">
            <?php if(get_transient('first_time_active')) { ?>
                <div class="congratz cfps-mb-4">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/star.webp' ?>" class="!cfps-h-[25px] !cfps-ml-0"/>
                    <div>Congratulations! Now you have the Wallet with which you can return up to 10% in rewards!</div>
                </div>
            <?php } ?>
            <div class="paynocchio-card-container">
                <div class="paynocchio-card" style="color:<?php echo $accent_text_color; ?>; background:<?php echo $accent_color; ?>">
                    <?php if ($paynocchio_embleme_url) { ?>
                        <img src="<?php echo $paynocchio_embleme_url; ?>" class="on_card_embleme" />
                    <?php }
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
                                $<span class="paynocchio-numbers paynocchio-balance-value" data-balance="<?php echo $wallet_info['balance'] ?>"><?php echo $wallet_balance ?></span>
                            </div>
                        </div>
                        <div class="paynocchio-bonuses">
                            <div>
                                Bonuses
                            </div>
                            <div class="amount">
                                <span class="paynocchio-numbers paynocchio-bonus-value" data-bonus="<?php echo $wallet_info['bonuses'] ?>"><?php echo $wallet_bonuses ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="paynocchio-card-number">
                        <div><?php echo chunk_split(strval($wallet_info['card_number']), 4, '</div><div>'); ?></div>
                    </div>
                </div>
            </div>
            <div class="paynocchio_account_page_buttons">
                <a href="#" class="<?php echo $paynocchio_rounded_class; ?> paynocchio_topup cfps-flex cfps-flex-row cfps-items-center" data-modal=".topUpModal" style="background:<?php echo $accent_color; ?>; color:<?php echo $accent_text_color; ?>; border-color:<?php echo $accent_color; ?>;">
                    <svg class="cfps-h-[20px] cfps-w-[20px] cfps-mr-1 cfps-inline-block" enable-background="new 0 0 50 50" version="1.1" viewBox="0 0 50 50" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect fill="none" height="50" width="50"/>
                        <line fill="none" stroke="<?php echo $accent_text_color; ?>" stroke-miterlimit="10" stroke-width="4" x1="9" x2="41" y1="25" y2="25"/>
                        <line fill="none" stroke="<?php echo $accent_text_color; ?>" stroke-miterlimit="10" stroke-width="4" x1="25" x2="25" y1="9" y2="41"/>
                    </svg>
                    Add money
                </a>
                <?php if ($allow_withdrawal && $wallet_balance > 0) { ?>
                <a href="#" class="<?php echo $paynocchio_rounded_class; ?> paynocchio_withdrawal cfps-flex cfps-flex-row cfps-items-center" data-modal=".withdrawModal" style="border-color:<?php echo $accent_color; ?>; color:<?php echo $accent_color; ?>">
                    Withdraw money
                </a>
                <?php } ?>
            </div>
        </div>
        <?php if(isset($wallet_info['status'])) { ?>
            <div class="paynocchio-profile-block <?php echo $paynocchio_rounded_class; ?> <?php echo $paynocchio_darkmode_class; ?>">
                <div class="paynocchio-profile-actions">
                    <div class="cfps-mb-4 cfps-flex cfps-flex-row cfps-gap-2">
                        <div>Wallet Status:</div>
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="wallet_status" class="cfps-font-bold"><?php echo $wallet_info['status'] ?></span>
                    </div>
                    <?php if ($wallet_info['status'] !== 'BLOCKED') { ?>
                        <div class="paynocchio-actions-btns !cfps-justify-start">
                            <?php if($wallet_info['status'] === "ACTIVE") { ?>
                                <a href="#" class="paynocchio_button btn-blue paynocchio_colored <?php echo $paynocchio_rounded_class; ?>" data-modal=".suspendModal">Suspend wallet</a>
                            <?php } elseif ($wallet_info['status'] === "SUSPEND") { ?>
                                <a href="#" class="paynocchio_button btn-blue paynocchio_colored <?php echo $paynocchio_rounded_class; ?>" data-modal=".reactivateModal">Reactivate wallet</a>
                            <?php } ?>
                            <a href="#" class="paynocchio_button btn-gray <?php echo $paynocchio_rounded_class; ?>" data-modal=".blockModal">
                                Block wallet
                            </a>
                        </div>
                    <?php } ?>
                    <?php if($wallet_info['status'] === 'BLOCKED') { ?>
                        <div class="cfps-flex cfps-flex-row cfps-gap-8 cfps-items-center">
                            <button data-modal=".deleteModal" class="cfps-btn-primary cfps-whitespace-nowrap <?php echo $paynocchio_rounded_class; ?>">Delete Wallet</button>
                            <div class="">If you have blocked your wallet by accident, please contact <a href="mailto:support@kopybara.com" class="cfps-text-blue-500">technical support</a> before deleting your wallet!</div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php } else { ?>
            <div class="paynocchio_error_notification <?php echo $paynocchio_rounded_class; ?>">
                <svg class="cfps-max-w-[100px] cfps-mx-auto cfps-mb-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm0-8v6h2V7h-2z" fill="#d4d4d4" class="fill-000000"></path>
                </svg>
                <div class="cfps-mb-4">
                    If you see this page, our wallet service issues an error "<span id="wallet_status" class="cfps-font-bold"><?php echo $wallet_info['status'] ?></span>"
                </div>
                <div>To solve the problem, please contact <b><?php echo get_bloginfo('name') ?></b> support</div>
            </div>
        <?php } ?>
<?php } ?>