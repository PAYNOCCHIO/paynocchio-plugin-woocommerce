<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $user_paynocchio_wallet_id = get_user_meta($current_user->ID, PAYNOCCHIO_WALLET_KEY, true);
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

<div class="modal topUpModal">
    <div class="container">
        <div class="header">
            <h3>Deposit</h3>
            <button class="close">&times;</button>
        </div>
        <div class="content">
            <p class="cfps-text-gray-500">
                From
            </p>
            <div class="card-variants">
                <div class="card-var current-card" data-pan="1356567423522373">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/mc.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <p>1356 5674 2352 2373</p>
                    </div>
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_d.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-inline-block" />
                </div>
                <div class="card-var" data-pan="3727844328348156">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/vs.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <p>3727 8443 2834 8156</p>
                    </div>
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_d.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-inline-block" />
                </div>
                <div class="card-var" data-pan="">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img data-modal=".paymentMethodModal" src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/plus_b.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <p data-modal=".paymentMethodModal">Add new payment method</p>
                    </div>
                </div>
            </div>
            <input type="hidden" id="source-card" name="source-card" value="" />

            <div class="top-up-amount-container cfps-mt-8 lg:cfps-mt-12 cfps-flex cfps-flex-row">
                <p class="cfps-text-3xl">$</p>
                <input type="number" step="0.01" class="cfps-bg-white cfps-border-0 cfps-text-3xl !cfps-p-0 focus:!cfps-outline-none"
                       name="amount" id="top_up_amount" value="1000" />
                <?php wp_nonce_field( 'paynocchio_ajax_top_up', 'ajax-top-up-nonce' ); ?>
            </div>

            <div class="top-up-variants cfps-flex cfps-flex-row cfps-items-center cfps-mt-8 lg:cfps-mt-12 cfps-gap-x-2">
                <a class="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer" id="variant_1000">
                    $1000
                </a>
                <a class="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer" id="variant_2000">
                    $2000
                </a>
                <a class="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer" id="variant_5000">
                    $5000
                </a>
                <a class="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer" id="variant_10000">
                    $10 000
                </a>
                <a class="cfps-bg-gray-100 cfps-px-4 cfps-py-3 cfps-rounded-lg cfps-text-xl cfps-cursor-pointer" id="variant_15000">
                    $15 000
                </a>
            </div>
            <p class="cfps-flex cfps-flex-row cfps-items-center cfps-mt-4 cfps-gap-x-0.5">
                The sending bank may charge a fee.<a href="#">Here's how to avoid it.</a>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_rb.png' ?>"
                     class="cfps-h-[18px] cfps-w-[18px] cfps-inline-block" />
            </p>

            <div class="autodeposit cfps-flex cfps-flex-row cfps-items-center cfps-mt-8 lg:cfps-mt-12 cfps-gap-x-2">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/i-gr.png' ?>"
                     class="cfps-h-[18px] cfps-w-[16px] cfps-mr-1 cfps-inline-block" />
                Autodeposit
                <div class="toggle-autodeposit">
                    <p>ON</p>
                    <div class="toggler"></div>
                    <p>OFF</p>
                </div>
                <input class="hidden" value="0" name="autodeposit" id="autodeposit" />
            </div>
        </div>
        <div class="footer">
            <div>
                <button id="top_up_button"
                        type="button"
                        class="cfps-btn-primary">
                    Top up
                    <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <div class="message"></div>
            </div>
        </div>
    </div>
    </form>
</div>





<div class="modal paymentMethodModal">
    <div class="container">
        <div class="header">
            <h3>Please add your card</h3>
            <button class="close">&times;</button>
        </div>
        <div class="content modal-add-card">
            <div class="cfps-flex cfps-flex-col cfps-gap-y-4">
                <div class="cfps-grid cfps-grid-cols-[1fr_1fr] cfps-gap-4">
                    <div class="">
                        <label for="card-country">Country<span class="cfps-text-red-500">*</span></label>
                        <select name="card-country">
                            <option value="USA">United States</option>
                            <option value="GBR">United Kingdom</option>
                        </select>
                    </div>
                    <div class="">
                        <label for="card-city">City<span class="cfps-text-red-500">*</span></label>
                        <input type="text" class="" name="card-city" />
                    </div>
                </div>
                <div class="cfps-grid cfps-gap-4">
                    <div class="cfps-w-auto">
                        <label for="card-address">Address<span class="cfps-text-red-500">*</span></label>
                        <input type="text" class="" name="card-address" />
                    </div>
                </div>
                <div class="cfps-grid cfps-gap-4">
                    <div class="cfps-w-auto">
                        <label for="card-address1">Address line 1<span class="cfps-text-red-500">*</span></label>
                        <input type="text" class="" name="card-address1" />
                    </div>
                </div>
                <div class="cfps-grid cfps-grid-cols-[1fr_1fr] cfps-gap-4">
                    <div class="">
                        <label for="card-state">State<span class="cfps-text-red-500">*</span></label>
                        <input type="text" class="" name="card-state" />
                    </div>
                    <div class="">
                        <label for="card-zip">ZIP code<span class="cfps-text-red-500">*</span></label>
                        <input type="text" class="" name="card-zip" />
                    </div>
                </div>
                <div class="cfps-grid cfps-gap-4">
                    <div class="cfps-w-auto">
                        <label for="card-pan">Bank Card Number<span class="cfps-text-red-500">*</span></label>
                        <input type="text" class="" name="card-pan" />
                    </div>
                </div>
                <div class="cfps-grid cfps-grid-cols-[1fr_1fr] cfps-gap-4">
                    <div class="">
                        <label for="card-expire">Card expiration date<span class="cfps-text-red-500">*</span></label>
                        <input type="text" class="" name="card-expire" />
                    </div>
                    <div class="">
                        <label for="card-cvv">CVV code<span class="cfps-text-red-500">*</span></label>
                        <input type="text" class="" name="card-cvv" />
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div>
                <button id="payment_method_button"
                        type="button"
                        class="cfps-btn-primary close">
                    Contunue
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

<div class="modal withdrawModal">
    <div class="container">
        <div class="header">
            <h3>Withdraw</h3>
            <button class="close">&times;</button>
        </div>
        <div id="witdrawForm" class="content">
            <div class="mb-4">Current balance: <span class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet_balance ?? 0?></span></span></div>
            <div class="top-up-amount-container cfps-mb-8 cfps-flex">
                <p class="cfps-text-3xl">$</p>
                <input type="number" step="0.01" class="cfps-bg-white cfps-border-0 cfps-text-3xl !cfps-p-0 focus:!cfps-outline-none"
                       name="amount" id="withdraw_amount" value="0" />
                <?php wp_nonce_field( 'paynocchio_ajax_withdraw', 'ajax-withdraw-nonce' ); ?>
            </div>

            <p class="cfps-text-gray-500">
                To
            </p>
            <div class="card-variants">
                <div class="card-var current-card" data-pan="1356567423522373">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/mc.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <p>1356 5674 2352 2373</p>
                    </div>
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_d.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-inline-block" />
                </div>
                <div class="card-var" data-pan="3727844328348156">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/vs.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <p>3727 8443 2834 8156</p>
                    </div>
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_d.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-inline-block" />
                </div>
                <div class="card-var" data-pan="">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img data-modal=".paymentMethodModal" src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/plus_b.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <p data-modal=".paymentMethodModal">Add new payment method</p>
                    </div>
                </div>
            </div>
            <input type="hidden" id="source-card2" name="source-card2" value="" />
        </div>
        <div class="footer">
            <div>
                <button id="withdraw_button"
                        type="button"
                        class="cfps-btn-primary">
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