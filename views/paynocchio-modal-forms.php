<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
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
    $minimum_topup_amount = $wallet_structure['minimum_topup_amount'];
    $card_balance_limit = $wallet_structure['card_balance_limit'];
    $rewarding_rules = $wallet_structure['rewarding_group']->rewarding_rules;

    // STYLING OPTIONS //
    $paynocchio_classes = '';
    $accent_color = '#3b82f6';
    $accent_text_color = '#ffffff';
    $settings = get_option( 'woocommerce_paynocchio_settings');
    if($settings) {
        $paynocchio_classes .= array_key_exists('darkmode', $settings) && $settings['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
        $paynocchio_classes .= array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';
        $paynocchio_rounded_class = array_key_exists('rounded', $settings) && $settings['rounded'] == 'yes' ? 'cfps-rounded-lg' : '';
        $paynocchio_embleme_url = array_key_exists('embleme_url', $settings) && $settings['embleme_url'] ? $settings['embleme_url'] : '';

        if (array_key_exists('accent_color', $settings)) {
            $accent_color = get_option( 'woocommerce_paynocchio_settings')['accent_color'];
        }

        if (array_key_exists('accent_text_color', $settings)) {
            $accent_text_color = get_option( 'woocommerce_paynocchio_settings')['accent_text_color'];
        }
    }
?>

<div class="modal topUpModal <?php echo $paynocchio_classes; ?>">
    <div class="close-modal close"></div>
    <div class="container">
        <div class="header">
            <h3>Deposit</h3>
            <button class="close">&times;</button>
        </div>
        <div class="content">
            <div class="cfps-mb-4 cfps-text-lg">
                Please enter amount you want to add to you wallet. After clicking "Add money" you will be redirected to Stripe for secure money transfer.
            </div>
            <div class="cfps-mb-4">The minimum replenishment amount for this card is $<span id="minimum_topup_amount"><?php echo $minimum_topup_amount; ?></span>.</div>
            <div class="cfps-mb-6">Card limit is $<span id="card_balance_limit"><?php echo $card_balance_limit; ?></span>.</div>
            <div class="top-up-amount-container cfps-mt-8 lg:cfps-mt-12 cfps-flex cfps-flex-row cfps-justify-between">
                <div>
                    <span class="cfps-text-3xl">$</span>
                    <input type="number" name="amount" id="top_up_amount"
                           min="<?php echo $minimum_topup_amount; ?>"
                           value="<?php echo $minimum_topup_amount; ?>"
                           step="0.01" placeholder="<?php echo $minimum_topup_amount; ?>"
                    />
                    <input type="hidden" name="redirect_url" value="" />
                    <?php wp_nonce_field( 'paynocchio_ajax_top_up', 'ajax-top-up-nonce' ); ?>
                </div>
            </div>

            <div class="top-up-variants">
                <a id="variant_1000">
                    $1000
                </a>
                <a id="variant_2000">
                    $2000
                </a>
                <a id="variant_5000">
                    $5000
                </a>
                <a id="variant_10000">
                    $10 000
                </a>
                <a id="variant_15000">
                    $15 000
                </a>
            </div>
            <!--<p class="cfps-flex cfps-flex-row cfps-items-center cfps-mt-4 cfps-gap-x-0.5">
                The sending bank may charge a fee.<a href="#">Here's how to avoid it.</a> >
            </p>-->

           <!-- <div class="autodeposit cfps-flex cfps-flex-row cfps-items-center cfps-mt-8 lg:cfps-mt-12 cfps-gap-x-2">
                <img src="<?php /*echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/i-gr.png' */?>"
                     class="cfps-h-[18px] cfps-w-[16px] cfps-mr-1 cfps-inline-block" />
                Autodeposit
                <div class="toggle-autodeposit">
                    <p>ON</p>
                    <div class="toggler"></div>
                    <p>OFF</p>
                </div>
                <input type="hidden" value="0" name="autodeposit" id="autodeposit" />
            </div>-->

        </div>
        <div class="footer">
            <div>
                <button id="top_up_button"
                        type="button" class="cfps-btn-primary paynocchio_button paynocchio_colored">
                    Add money
                    <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <div class="message cfps-text-balance" id="topup_message">You will get <span id="bonusesCounter">2</span> bonuses.</div>
                <div id="commission_message"></div>
            </div>
        </div>
    </div>
    </form>
</div>

<div class="modal withdrawModal <?php echo $paynocchio_classes; ?>">
    <div class="close-modal close"></div>
    <div class="container">
        <div class="header">
            <h3>Withdraw</h3>
            <button class="close">&times;</button>
        </div>
        <div id="witdrawForm" class="content">
            <div class="cfps-mb-8 cfps-text-xl">Current balance: <span class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value" data-balance="<?php echo $wallet_balance; ?>"><?php echo $wallet_balance; ?></span></span></div>
            <div class="withdraw-amount-container cfps-mb-8 cfps-flex">
                <p class="cfps-text-3xl">$</p>
                <input type="number" step="0.01" class="!cfps-bg-transparent !cfps-border-0 !cfps-shadow-none cfps-text-3xl !cfps-p-0 focus:!cfps-outline-none"
                       name="amount" id="withdraw_amount" placeholder="0" value="0" />
                <?php wp_nonce_field( 'paynocchio_ajax_withdraw', 'ajax-withdraw-nonce' ); ?>
            </div>
        </div>
        <div class="footer">
            <div>
                <button id="withdraw_button"
                        type="button"
                        class="cfps-btn-primary paynocchio_button paynocchio_colored">
                    Withdraw
                    <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <div class="message"></div>
            </div>
        </div>
    </div>
</div>

    <?php
    /** Suspension and Deletion modals */
    ?>
    <?php wp_nonce_field( 'paynocchio_ajax_set_status', 'ajax-status-nonce' ); ?>
    <div class="modal suspendModal <?php echo $paynocchio_classes; ?>">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Suspend your Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="cfps-mb-4">Suspension blocks all transactions until further actions.</p>
                <p>
                    <strong>Are you sure you want to suspend your wallet?</strong>
                </p>
            </div>
            <div class="footer">
                <div>
                    <button id="suspend_button"
                            type="button"
                            class="cfps-btn-primary close cfps-rounded-lg">
                        Suspend
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button
                            class="cfps-btn-primary close cfps-rounded-lg btn-gray"
                            type="button">Cancel</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal reactivateModal <?php echo $paynocchio_classes; ?>">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Reactivate your Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="cfps-mb-4">After reactivating the wallet you can continue shopping.</p>
                <p>
                    <strong>Are you sure you want to reactivate your wallet?</strong>
                </p>
            </div>
            <div class="footer">
                <div>
                    <button id="reactivate_button"
                            type="button"
                            class="cfps-btn-primary close cfps-rounded-lg">
                        Reactivate
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button
                            class="cfps-btn-primary close cfps-rounded-lg btn-gray"
                            type="button">Cancel</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal blockModal <?php echo $paynocchio_classes; ?>">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Block your Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="cfps-mb-4">Are you sure you want to BLOCK your wallet?</p>
                <p class="cfps-font-bold">Attention! This action cannot be undone.</p>
            </div>
            <div class="footer">
                <div>
                    <button id="block_button"
                            type="button"
                            class="cfps-btn-primary close cfps-rounded-lg">
                        Block
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button
                            class="cfps-btn-primary close cfps-rounded-lg btn-gray"
                            type="button">Cancel</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal deleteModal <?php echo $paynocchio_classes; ?>">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Delete your Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="cfps-mb-4">Are you sure you want to DELETE your wallet?</p>
                <p class="cfps-font-bold">Attention! If you have blocked your wallet by accident, please contact <a href="mailto:support@kopybara.com" class="cfps-text-blue-500">technical support</a> before deleting your wallet!</p>
            </div>
            <div class="footer">
                <div>

                    <button id="delete_button"
                            type="button"
                            class="cfps-btn-primary close cfps-rounded-lg">
                        Delete
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'paynocchio_ajax_delete_wallet', 'ajax-delete-nonce' ); ?>

                    <button
                            class="cfps-btn-primary close cfps-rounded-lg btn-gray"
                            type="button">Cancel</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>