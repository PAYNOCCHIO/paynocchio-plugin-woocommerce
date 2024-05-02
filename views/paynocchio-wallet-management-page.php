<?php
    $paynocchio = new Woocommerce_Paynocchio();
    $wallet = $paynocchio->get_paynocchio_wallet_info();

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

    echo do_shortcode('[paynocchio_modal_forms]');
?>

<?php if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY, true)) { ?>
    <div class="paynocchio-profile-block <?php echo $paynocchio_rounded_class; ?>">
        <?php echo do_shortcode('[paynocchio_activation_block]'); ?>
    </div>
<?php } else { ?>
    <div class="paynocchio-profile-block <?php echo $paynocchio_rounded_class; ?> <?php if ($wallet['status'] !== 'ACTIVE') { ?>cfps-disabled<?php } ?>">
        <div class="paynocchio-card-container">
            <div class="paynocchio-card">
                <img class="cfps-block !cfps-mx-auto !cfps-w-full !cfps-max-w-[350px]" src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/blank_card.webp' ?>" />
                <?php if ($paynocchio_embleme_url) { ?>
                    <img src="<?php echo $paynocchio_embleme_url; ?>" class="on_card_embleme" />
                <?php }
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
            </div>
        </div>
        <div class="paynocchio-actions-btns cfps-mt-8">
            <div class="autodeposit cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2">
                <!-- <img src="<?php /*echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/i-gr.png' */?>"
                             class="cfps-h-[18px] cfps-w-[16px] cfps-mr-1 cfps-inline-block" />
                        Autodeposit
                        <div class="toggle-autodeposit">
                            <p>ON</p>
                            <div class="toggler"></div>
                            <p>OFF</p>
                        </div>
                        <input type="hidden" value="0" name="autodeposit" id="autodeposit" />-->
            </div>
            <div class="paynocchio-actions-btns">
                <a href="#" class="paynocchio_button btn-blue paynocchio_colored <?php echo $paynocchio_rounded_class; ?>" data-modal=".topUpModal">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/plus.png' ?>"
                         class="cfps-h-[24px] cfps-w-[24px] cfps-mr-1 cfps-inline-block" />
                    Add money
                </a>
                <a href="#" class="paynocchio_button btn-gray <?php echo $paynocchio_rounded_class; ?>" data-modal=".withdrawModal">
                    Withdraw
                </a>
            </div>
        </div>
    </div>

    <?php if(isset($wallet['status'])) { ?>
        <div class="paynocchio-profile-block <?php echo $paynocchio_rounded_class; ?>">
            <div class="paynocchio-profile-actions">
                <div class="cfps-mb-4 cfps-flex cfps-flex-row cfps-gap-2">
                    <div>Wallet Status:</div>
                    <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="wallet_status" class="cfps-font-bold"><?php echo $wallet['status'] ?></span>
                </div>
                <?php if ($wallet['status'] !== 'BLOCKED') { ?>
                    <div class="paynocchio-actions-btns !cfps-justify-start">
                        <?php if($wallet['status'] === "ACTIVE") { ?>
                            <a href="#" class="paynocchio_button btn-blue paynocchio_colored <?php echo $paynocchio_rounded_class; ?>" data-modal=".suspendModal">Suspend wallet</a>
                        <?php } elseif ($wallet['status'] === "SUSPEND") { ?>
                            <a href="#" class="paynocchio_button btn-blue paynocchio_colored <?php echo $paynocchio_rounded_class; ?>" data-modal=".reactivateModal">Reactivate wallet</a>
                        <?php } ?>
                        <a href="#" class="paynocchio_button btn-gray <?php echo $paynocchio_rounded_class; ?>" data-modal=".blockModal">
                            Block wallet
                        </a>
                    </div>
                <?php } ?>
                <?php if($wallet['status'] === 'BLOCKED') { ?>
                    <div class="cfps-flex cfps-flex-row cfps-gap-8 cfps-items-center">
                        <button data-modal=".deleteModal" class="cfps-btn-primary cfps-whitespace-nowrap <?php echo $paynocchio_rounded_class; ?>">Delete Wallet</button>
                        <div class="">If you have blocked your wallet by accident, please contact <a href="mailto:support@kopybara.com" class="cfps-text-blue-500">technical support</a> before deleting your wallet!</div>
                    </div>

                <?php } ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>