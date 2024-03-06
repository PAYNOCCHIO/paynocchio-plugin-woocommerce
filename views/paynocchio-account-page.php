<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<?php $current_user = wp_get_current_user(); ?>

<section class="paynocchio">
    <div class="paynocchio-account">
        <div class="paynocchio-embleme">
            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/kopybara-logo.png' ?>" />
        </div>
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
            <a class="choosen profile_toggle" onclick="paynocchio_tab_toggle('profile')">
                Profile
            </a>
            <a class="my-trips_toggle" onclick="paynocchio_tab_toggle('my-trips')">
                My trips
            </a>
        </div>

        <div class="paynocchio_tabs">
            <div class="paynocchio-profile-body paynocchio-tab-body visible">
                <?php if (!get_user_meta(get_current_user_id(), 'paynoccio_wallet')) { ?>
                    <div class="paynocchio-profile-block cfps-bg-white">
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
                    <div class="paynocchio-profile-block cfps-bg-blue-500">
                        <div class="cfps-grid cfps-grid-cols-[1fr_100px_200px_35px] cfps-gap-x-6">
                            <h2 class="cfps-font-bold cfps-text-2xl cfps-text-white">
                                Kopybara.Pay
                            </h2>
                            <h2 class="cfps-font-bold cfps-text-2xl cfps-text-white">
                                $0
                            </h2>
                            <h2 class="cfps-font-bold cfps-text-2xl cfps-text-white">
                                0 bonuses
                            </h2>
                            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/arr_r.png' ?>" />
                        </div>
                    </div>
                <?php } ?>

                <div class="paynocchio-profile-block cfps-bg-white">
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
                <div class="paynocchio-profile-block cfps-bg-white">
                    <h2 class="cfps-font-bold cfps-text-xl cfps-mb-4">My Trips</h2>
                    <p>No trips found!</p>
                </div>
            </div>
        </div>

        <div class="paynocchio-consents">
            <p class="cfps-text-slate-500">I agree to <a href="#" class="cfps-text-slate-500 cfps-underline">Kopybara Terms & Conditions</a> and <a href="#" class="cfps-text-slate-500 cfps-underline">rules of Kopybara.Pay Priority program</a> </p>
        </div>
    </div>
</section>

<script>

</script>

<?php