<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php
    $current_user = wp_get_current_user();
    $user_paynocchio_wallet_id = get_user_meta($current_user->ID, 'paynoccio_wallet', true);
    $paynocchio_wallet = new Woocommerce_Paynocchio_Wallet($current_user->ID);
    $json_response = $paynocchio_wallet->getWalletBalance($user_paynocchio_wallet_id);
    $user_paynocchio_wallet = $paynocchio_wallet->getWalletById($user_paynocchio_wallet_id);
    if($user_paynocchio_wallet['status_code'] === 200) {
        $wallet_balance = $json_response['balance'];
        $wallet_bonuses = $json_response['bonuses'];
    }

    $wallet_pan = 2243123434652243;
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
                        <p class="cfps-text-2xl cfps-font-semibold">0</p>
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

                <?php } ?>


                <?php echo do_shortcode('[paynocchio_payment_widget]'); ?>

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
                                        $<?php echo $wallet_balance ?? ''; ?>
                                    </div>
                                </div>
                                <div class="paynocchio-bonuses">
                                    <div>
                                        Bonuses
                                    </div>
                                    <div class="amount">
                                        <?php echo $wallet_bonuses ?? ''; ?>
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
                        <div class="">
                            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/i-gr.png' ?>"
                                 class="cfps-h-[18px] cfps-w-[16px] cfps-mr-1 cfps-inline-block" />
                            Auto-refill
                        </div>
                        <div class="paynocchio-actions-btns">
                            <a href="#" class="btn-blue">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/plus.png' ?>"
                                     class="cfps-h-[24px] cfps-w-[24px] cfps-mr-1 cfps-inline-block" />
                                Add money
                            </a>
                            <a href="#" class="btn-gray">
                                Withdraw
                            </a>
                        </div>
                    </div>
                </div>

                <div class="paynocchio-profile-block">
                    <h2>Payments methods</h2>
                    <a href="" class="btn-blue">Add payment method</a>
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
    <a href="#" data-modal=".myModal">Open Modal</a>
    <div class="modal myModal">
        <div class="container">
            <div class="header">
                <h1>My Modal</h1>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <input type="text" id="top_up_amount">
                <button id="top_up_button"
                        type="button"
                        class="cfps-btn-primary">
                    Top up
                    <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <?php wp_nonce_field( 'paynocchio_ajax_top_up', 'ajax-top-up-nonce' ); ?>
            </div>
            <div class="footer">
                <button class="close">Close</button>
            </div>
        </div>
    </div>
</section>

<?php