<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $user_paynocchio_wallet_id = get_user_meta($current_user->ID, 'paynoccio_wallet', true);
    $user_paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($current_user->ID);
    $wallet_bal_bon = $user_paynocchio_wallet->getWalletBalance($user_paynocchio_wallet_id);

    $wallet_bal = $wallet_bal_bon['balance'];
    $wallet_bon = $wallet_bal_bon['bonuses'];
    $wallet_pan = $wallet_bal_bon['number'];
};
?>

<section class="paynocchio">
    <div class="paynocchio-account">
        <div class="paynocchio-embleme">
            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/kopybara-logo.png' ?>" />
        </div>
    <?php if (is_user_logged_in()) { ?>
        <div class="paynocchio-profile-info">
            <div class="paynocchio-profile-img">
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/profile.png' ?>" />
            </div>
            <div class="paynocchio-profile-text">
                <h2>
                   <?php echo $current_user->user_firstname. ' ' .$current_user->user_lastname; ?>
                </h2>
                <a href="#">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/plane.png' ?>"
                         class="cfps-h-[20px] cfps-w-[20px] cfps-mr-1 cfps-inline-block" />
                    Start earning bonuses
                </a>
                <div class="paynocchio-count">
                    <div>
                        <p class="cfps-text-2xl cfps-font-semibold"><span class="paynocchio-numbers paynocchio-bonus-value"></span></p>
                        <p>bonuses</p>
                    </div>
                    <div>
                        <p class="">0</p>
                        <p>flights</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="paynocchio-tab-selector">
            <a class="tab-switcher choosen" id="profile_toggle">
                Profile
            </a>
            <a class="tab-switcher" id="my-trips_toggle">
                My trips
            </a>
        </div>

        <div class="paynocchio_tabs">
            <div class="paynocchio-profile-body paynocchio-tab-body visible">
                <?php if (!get_user_meta(get_current_user_id(), 'paynoccio_wallet')) { ?>
                    <div class="paynocchio-profile-block">
                        <div class="cfps-grid cfps-grid-cols-[24px_1fr] cfps-gap-x-6">
                            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/i.png' ?>" />
                            <div class="">
                                <p class="cfps-mb-4">Join Kopybara.Pay program. Save document info to make quicker purchases, earn cashback bonuses, and buy premium tickets.</p>
                                <a href="/" class="cfps-btn-primary">Activate Kopybara.Pay</a>
                                <?php wp_nonce_field( 'paynocchio_ajax_activation', 'ajax-activation-nonce' ); ?>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="paynocchio-profile-block paynocchio-blue-badge">
                        <div class="cfps-grid cfps-grid-cols-[1fr_100px_200px_35px] cfps-gap-x-6">
                            <div>
                                Kopybara.Pay
                            </div>
                            <div>
                                $<span class="paynocchio-numbers paynocchio-balance-value"></span>
                            </div>
                            <div>
                                Bonuses: <span class="paynocchio-numbers paynocchio-bonus-value"></span>
                            </div>
                            <a class="tab-switcher cfps-cursor-pointer" id="wallet_toggle">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_r.png' ?>" />
                            </a>
                        </div>
                    </div>
                <?php } ?>

                <div class="paynocchio-profile-block">
                    <h2 class="cfps-font-bold cfps-text-xl cfps-mb-4">Personal Info</h2>
                    <div class="cfps-grid cfps-grid-cols-[1fr_1fr] cfps-gap-x-6">
                        <div class="">
                            <span class="cfps-font-semibold">Birthdate</span>: 11/11/1987
                        </div>
                        <div class="">
                            <span class="cfps-font-semibold">Gender</span>: Male
                        </div>
                    </div>
                </div>
            </div>

            <div class="paynocchio-my-trips-body paynocchio-tab-body">
                <div class="paynocchio-profile-block">
                    <h2>My Trips</h2>
                    <p>No trips found!</p>
                </div>
            </div>

            <div class="paynocchio-wallet-body paynocchio-tab-body">
                <div class="cfps-max-w-5xl cfps-mx-auto cfps-mt-8">
                    <a class="btn-back tab-switcher" id="profile_toggle">
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_back.png' ?>"
                        class="cfps-h-[20px] cfps-w-[20px] cfps-mr-1 cfps-inline-block" />
                        Profile
                    </a>
                </div>
                <div class="paynocchio-profile-block">
                    <h2>Kopybara.Pay</h2>
                    <div class="paynocchio-card-container">
                        <div class="paynocchio-card-face visible">
                            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/card-face.png' ?>" />
                            <a class="card-toggle"></a>
                            <div class="paynocchio-balance-bonuses">
                                <div class="paynocchio-balance">
                                    <div>
                                        Balance
                                    </div>
                                    <div class="amount">
                                        $<span class="paynocchio-numbers paynocchio-balance-value"></span>
                                    </div>
                                </div>
                                <div class="paynocchio-bonuses">
                                    <div>
                                        Bonuses
                                    </div>
                                    <div class="amount">
                                        <span class="paynocchio-numbers paynocchio-bonus-value"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="paynocchio-card-number-mask">
                                <span class="cfps-text-gray-300 cfps-mr-4">● ● ● ●</span> <?php echo substr(strval($wallet_pan), -4); ?>
                            </div>
                        </div>
                        <div class="paynocchio-card-back">
                            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/card-back.png' ?>" />
                            <a class="card-toggle"></a>
                            <div class="paynocchio-card-number">
                                <div><?php echo chunk_split(strval($wallet_pan), 4, '</div><div>'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="paynocchio-actions-btns cfps-mt-8 lg:cfps-mt-16">
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
                        <div class="paynocchio-actions-btns">
                            <a href="#" class="btn-blue" data-modal=".topUpModal">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/plus.png' ?>"
                                     class="cfps-h-[24px] cfps-w-[24px] cfps-mr-1 cfps-inline-block" />
                                Add money
                            </a>
                            <a href="#" class="btn-gray" data-modal=".withdrawModal">
                                Withdraw
                            </a>
                        </div>
                    </div>
                </div>

                <div class="paynocchio-profile-block">
                    <h2>Payments methods</h2>
                    <a href="" class="btn-blue" data-modal=".paymentMethodModal">Add payment method</a>
                </div>

                <div class="paynocchio-profile-block">
                    <h2>History</h2>
                    <div class="history-list">
                        <div class="history-item">
                            <div class="history-info cfps-text-gray-500">
                                Today
                            </div>
                            <div class="history-amount">
                                $0
                            </div>
                        </div>
                        <div class="history-item">
                            <div class="history-info">
                                <p class="cfps-font-semibold">NY - LA</p>
                                <p>January 3</p>
                            </div>
                            <div class="history-amount">
                                - $235.29
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    <?php } else {
        echo do_shortcode('[paynocchio_registration_block]');
    } ?>

        <div class="paynocchio-consents">
            <p class="cfps-text-slate-500">I agree to <a href="#" class="cfps-text-slate-500 cfps-underline">Kopybara Terms & Conditions</a> and <a href="#" class="cfps-text-slate-500 cfps-underline">rules of Kopybara.Pay Priority program</a> </p>
        </div>
    </div>
</section>

<?php
// TODO
/* PLEASE do not delete it yet, I will finalize it later
$string = simplexml_load_file("https://www.artlebedev.ru/country-list/xml/");
//Обратите внимание на вложенность с помощью
// echo '<pre>';  print_r($xml);  echo '</pre>';

function xml2array ( $xmlObject, $out = array () )
{
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

    return $out;
}

$array = xml2array($string);
$array = $array['country'];

foreach($array as $item){
    $item = xml2array($item);
}

//   print_r($array);

foreach($array['country'] as $item){
   // print_r($item);
    print_r($item['english']);
    //echo '<option value="">'.$item['iso'].'</option>';
} */
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
            <div class="mb-4">Current balance: <span class="cfps-font-semibold">$<span class="paynocchio-numbers paynocchio-balance-value"></span></span></div>
            <div class="top-up-amount-container cfps-mb-8 cfps-flex">
                <p class="cfps-text-3xl">$</p>
                <input type="number" step="0.01" class="cfps-bg-white cfps-border-0 cfps-text-3xl !cfps-p-0 focus:!cfps-outline-none"
                       name="amount" id="withdraw_amount" placeholder="Type a number" />
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
            <input type="hidden" id="source-card" name="source-card" value="" />
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