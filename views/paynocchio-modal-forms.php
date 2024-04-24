<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
if (is_user_logged_in()) {
    $paynocchio = new Woocommerce_Paynocchio();
    $wallet = $paynocchio->get_paynocchio_wallet_info();

    $paynocchio_classes = '';
    $settigns = get_option( 'woocommerce_paynocchio_settings');
    $paynocchio_classes .= array_key_exists('darkmode', $settigns) && $settigns['darkmode'] == 'yes' ? 'paynocchio_dark_mode ' : '';
    $paynocchio_classes .= array_key_exists('rounded', $settigns) && $settigns['rounded'] == 'yes' ? 'paynocchio_rounded ' : '';
?>

<div class="modal topUpModal <?php echo $paynocchio_classes; ?>">
    <div class="close-modal close"></div>
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
                <div class="card-var current-card" data-pan="<?php echo $wallet['card_number']; ?>">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/mc.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <div>1356 5674 2352 2373</div>
                    </div>
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_d.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-inline-block" />
                </div>
                <div class="card-var" data-pan="3727844328348156">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/vs.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <div>3727 8443 2834 8156</div>
                    </div>
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_d.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-inline-block" />
                </div>
                <div class="card-var" data-pan="">
                    <div class="cfps-flex cfps-flex-row cfps-gap-x-4 cfps-items-center">
                        <img data-modal=".paymentMethodModal" src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/plus_b.png' ?>" class="cfps-h-[30px] cfps-w-[30px] cfps-mr-1 cfps-inline-block" />
                        <div data-modal=".paymentMethodModal">Add new payment method</div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="source-card" name="source-card" value="" />

            <div class="top-up-amount-container cfps-mt-8 lg:cfps-mt-12 cfps-flex cfps-flex-row">
                <span class="cfps-text-3xl">$</span>
                <input type="number" step="0.01" class="!cfps-bg-transparent !cfps-border-0 !cfps-shadow-none cfps-text-3xl !cfps-p-0 focus:!cfps-outline-none"
                       name="amount" id="top_up_amount" placeholder="0" data-value="" />
                <?php wp_nonce_field( 'paynocchio_ajax_top_up', 'ajax-top-up-nonce' ); ?>
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
            <p class="cfps-flex cfps-flex-row cfps-items-center cfps-mt-4 cfps-gap-x-0.5">
                The sending bank may charge a fee.<a href="#">Here's how to avoid it.</a> >
            </p>

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

<div class="modal paymentMethodModal <?php echo $paynocchio_classes; ?>">
    <div class="close-modal close"></div>
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
                        class="cfps-btn-primary paynocchio_button paynocchio_colored close">
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

<div class="modal withdrawModal <?php echo $paynocchio_classes; ?>">
    <div class="close-modal close"></div>
    <div class="container">
        <div class="header">
            <h3>Withdraw</h3>
            <button class="close">&times;</button>
        </div>
        <div id="witdrawForm" class="content">
            <div class="cfps-mb-8 cfps-text-xl">Current balance: <span class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value" data-balance="<?php echo $wallet['balance'] ;?>"><?php echo $wallet['balance'] ;?></span></span></div>
            <div class="withdraw-amount-container cfps-mb-8 cfps-flex">
                <p class="cfps-text-3xl">$</p>
                <input type="number" step="0.01" class="!cfps-bg-transparent !cfps-border-0 !cfps-shadow-none cfps-text-3xl !cfps-p-0 focus:!cfps-outline-none"
                       name="amount" id="withdraw_amount" placeholder="0" value="0" />
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

<?php } ?>