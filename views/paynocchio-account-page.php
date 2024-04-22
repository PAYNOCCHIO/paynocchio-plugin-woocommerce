<?php
if (!defined('ABSPATH')) {
    die;
}
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

<?php if (is_user_logged_in()) {

    $paynocchio = new Woocommerce_Paynocchio();
    $wallet = $paynocchio->get_paynocchio_wallet_info();

    global $current_user;
?>
    <section class="paynocchio  <?php echo $paynocchio_classes; ?>">
        <div class="paynocchio-account">
            <div class="paynocchio-embleme">
                <img src="<?php echo $embleme_link; ?>" />
            </div>
            <div class="paynocchio-profile-info">
                <div class="paynocchio-profile-img">
                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/profile.png' ?>" />
                </div>
                <div class="paynocchio-profile-text">
                    <h2>
                        <?php echo $wallet['user']['first_name']. ' ' .$wallet['user']['last_name']; ?>
                    </h2>
                    <p>
                        <?php echo $current_user->user_email; ?>
                    </p>
                    <a href="#">
                        Start earning bonuses
                    </a>
                </div>
                <?php if(isset($wallet['status'])) { ?>
                    <div class="paynocchio-profile-actions">
                        <div class="cfps-mb-4 cfps-text-gray-500">Wallet Status:
                            <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="wallet_status" class="cfps-font-bold"><?php echo $wallet['status'] ?></span></div>
                        <?php if($wallet['status'] !== 'BLOCKED') { ?>
                            <label class="dropdown">
                                <div class="action-button">
                                    Manage wallet
                                </div>

                                <input type="checkbox" class="wallet-input" />

                                <ul class="wallet-menu">
                                    <?php if($wallet['status'] === "ACTIVE") { ?>
                                        <li><a href="#" data-modal=".suspendModal">Suspend wallet</a></li>
                                    <?php }
                                    if ($wallet['status'] === "SUSPEND") { ?>
                                        <li><a href="#" data-modal=".reactivateModal">Reactivate wallet</a></li>
                                    <?php } ?>

                                    <li><a class="cfps-text-red-500" href="#" data-modal=".blockModal">Block wallet</a></li>
                                </ul>
                            </label>
                        <?php } ?>

                        <?php if($wallet['status'] === 'BLOCKED') { ?>
                            <button data-modal=".deleteModal" class="cfps-btn-primary">Delete Wallet</button>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <div class="paynocchio-tab-selector">
                <a class="tab-switcher choosen" id="profile_toggle">
                    Profile
                </a>
                <a class="tab-switcher" id="my-orders_toggle">
                    My orders
                </a>
            </div>
            <div class="paynocchio_tabs">
                <div class="paynocchio-profile-body paynocchio-tab-body visible">
                    <?php if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY, true)) { ?>
                        <div class="paynocchio-profile-block">
                            <div class="cfps-grid cfps-grid-cols-[24px_1fr] cfps-gap-x-6">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/i.png' ?>" />
                                <div class="">
                                    <p class="cfps-mb-4">Join Paynocchio.Pay program. Save document info to make quicker purchases, earn cashback bonuses, and buy premium tickets.</p>
                                    <button id="paynocchio_activation_button" type="button" class="paynocchio_button paynocchio_colored cfps-btn-primary" value="">
                                        Activate Paynocchio.Pay
                                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                    <?php wp_nonce_field( 'paynocchio_ajax_activation', 'ajax-activation-nonce' ); ?>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="paynocchio-profile-block paynocchio-blue-badge paynocchio_colored">
                            <div class="cfps-flex cfps-flex-col lg:cfps-flex lg:cfps-flex-row cfps-justify-between cfps-gap-4">
                                <div>
                                    Paynocchio.Pay
                                </div>
                                <div>
                                    $<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet['balance'] ?? 0 ?></span>
                                </div>
                                <div>
                                    Bonuses: <span class="paynocchio-numbers paynocchio-bonus-value"><?php echo $wallet['bonuses'] ?? 0 ?></span>
                                </div>
                                <div>
                                    <a class="tab-switcher cfps-cursor-pointer" id="wallet_toggle">
                                        <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pjxzdmcgdmlld0JveD0iMCAwIDk2IDk2IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjx0aXRsZS8+PHBhdGggZD0iTTY5Ljg0MzcsNDMuMzg3NiwzMy44NDIyLDEzLjM4NjNhNi4wMDM1LDYuMDAzNSwwLDAsMC03LjY4NzgsOS4yMjNsMzAuNDcsMjUuMzktMzAuNDcsMjUuMzlhNi4wMDM1LDYuMDAzNSwwLDAsMCw3LjY4NzgsOS4yMjMxTDY5Ljg0MzcsNTIuNjEwNmE2LjAwOTEsNi4wMDkxLDAsMCwwLDAtOS4yMjNaIi8+PC9zdmc+" />
                                    </a>
                                </div>
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

                <div class="paynocchio-my-orders-body paynocchio-tab-body">
                    <div class="paynocchio-profile-block">
                        <h2>My orders</h2>
                        <div class="history-list">

                            <?php

                            $orders = wc_get_orders([
                                'numberposts' => -1,
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'customer_id'  => get_current_user_id(),
                            ]);

                            foreach ($orders as $order) {?>


                                <div class="history-item">
                                    <div class="history-info cfps-text-gray-500">
                                        #<?php echo $order->get_id(); ?>
                                    </div>
                                    <div class="cfps-uppercase">
                                        <?php echo $order->get_status(); ?>
                                    </div>
                                    <div class="history-info cfps-text-gray-500">
                                        <?php
                                        if( $date_completed = $order->get_date_completed() ){
                                            // Display the localized formatted date
                                            echo $date_completed->date_i18n('j F, Y');
                                        }?>
                                    </div>
                                    <div class="history-amount">
                                        <?php echo $order->get_total(); ?>
                                        <?php echo $order->get_currency(); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                 <?php if(isset($wallet['status']) && $wallet['status'] === 'ACTIVE') { ?>
                <div class="paynocchio-wallet-body paynocchio-tab-body">
                    <div class="cfps-max-w-5xl cfps-mx-auto cfps-mt-8">
                        <a class="paynocchio_colored paynocchio_button btn-back tab-switcher" id="profile_toggle">
                            < Profile
                        </a>
                    </div>
                    <div class="paynocchio-profile-block">
                        <h2>Paynocchio.Pay</h2>
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
                                            $<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet['balance'] ?></span>
                                        </div>
                                    </div>
                                    <div class="paynocchio-bonuses">
                                        <div>
                                            Bonuses
                                        </div>
                                        <div class="amount">
                                            <span class="paynocchio-numbers paynocchio-bonus-value"><?php echo $wallet['bonuses'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="paynocchio-card-number-mask">
                                    <span class="cfps-text-gray-300 cfps-mr-4">● ● ● ●</span> <?php echo substr(strval($wallet['card_number']), -4); ?>
                                </div>
                            </div>
                            <div class="paynocchio-card-back">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/card-back.png' ?>" />
                                <a class="card-toggle"></a>
                                <div class="paynocchio-card-number">
                                    <div><?php echo chunk_split(strval($wallet['card_number']), 4, '</div><div>'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="paynocchio-actions-btns cfps-mt-8 lg:cfps-mt-16">
                            <div class="autodeposit cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2">
                                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/i-gr.png' ?>"
                                     class="cfps-h-[18px] cfps-w-[16px] cfps-mr-1 cfps-inline-block" />
                                Autodeposit
                                <div class="toggle-autodeposit">
                                    <p>ON</p>
                                    <div class="toggler"></div>
                                    <p>OFF</p>
                                </div>
                                <input type="hidden" value="0" name="autodeposit" id="autodeposit" />
                            </div>
                            <div class="paynocchio-actions-btns">
                                <a href="#" class="paynocchio_button btn-blue paynocchio_colored" data-modal=".topUpModal">
                                    <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/plus.png' ?>"
                                         class="cfps-h-[24px] cfps-w-[24px] cfps-mr-1 cfps-inline-block" />
                                    Add money
                                </a>
                                <a href="#" class="paynocchio_button btn-gray" data-modal=".withdrawModal">
                                    Withdraw
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="paynocchio-profile-block">
                        <h2>Payments methods</h2>
                        <a href="" class="paynocchio_button btn-blue paynocchio_colored" data-modal=".paymentMethodModal">Add payment method</a>
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
            <?php } ?>
            </div>
            <div class="paynocchio-consents">
                <div class="cfps-text-slate-500"><a href="#" class="cfps-text-slate-500 cfps-underline">Paynocchio Terms & Conditions</a> • <a href="#" class="cfps-text-slate-500 cfps-underline">Rules of Paynocchio.Pay Priority program</a></div>
            </div>
        </div>
    </section>

    <?php
    /** Suspension and Deletion modals */
    ?>
    <div class="modal suspendModal">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Suspend Paynocchio Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p>Suspension blocks all transactions until further actions.</p>
                <p>Lorem ipsum.</p>
            </div>
            <div class="footer">
                <div>
                    <strong>Are you sure you want to suspend your wallet?</strong>
                    <button id="suspend_button"
                            type="button"
                            class="cfps-btn-primary close">
                        Yes
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'paynocchio_ajax_set_status', 'ajax-status-nonce' ); ?>

                    <button
                            class="cfps-btn-primary close"
                            type="button">No</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal reactivateModal">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Reactivate Paynocchio Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p>After reactivating the wallet you can continue shopping.</p>
            </div>
            <div class="footer">
                <div>
                    <strong>Are you sure you want to reactivate your wallet?</strong>
                    <button id="reactivate_button"
                            type="button"
                            class="cfps-btn-primary close">
                        Yes
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'paynocchio_ajax_set_status', 'ajax-status-nonce' ); ?>

                    <button
                            class="cfps-btn-primary close"
                            type="button">No</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal blockModal">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Block Paynocchio Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="cfps-font-bold cfps-text-red-500">Attention!</p>
            </div>
            <div class="footer">
                <div>
                    <strong>Are you sure you want to BLOCK your wallet?</strong>
                    <button id="block_button"
                            type="button"
                            class="cfps-btn-primary close">
                        Yes
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'paynocchio_ajax_set_status', 'ajax-status-nonce' ); ?>

                    <button
                            class="cfps-btn-primary close"
                            type="button">No</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal deleteModal">
        <div class="close-modal close"></div>
        <div class="container">
            <div class="header">
                <h3>Delete Paynocchio Wallet</h3>
                <button class="close">&times;</button>
            </div>
            <div class="content">
                <p class="cfps-font-bold cfps-text-red-500">Attention!</p>
            </div>
            <div class="footer">
                <div>
                    <strong>Are you sure you want to DELETE your wallet?</strong>
                    <button id="delete_button"
                            type="button"
                            class="cfps-btn-primary close">
                        Yes
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'paynocchio_ajax_delete_wallet', 'ajax-delete-nonce' ); ?>

                    <button
                            class="cfps-btn-primary close"
                            type="button">No</button>
                    <div class="message"></div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    echo do_shortcode('[paynocchio_registration_block]');
} ?>

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

<?php echo do_shortcode('[paynocchio_modal_forms]'); ?>