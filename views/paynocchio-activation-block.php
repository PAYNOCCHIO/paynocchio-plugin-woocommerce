<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<section class="paynocchio">
    <div class="article-body cfps-max-w-4xl cfps-mx-auto">
        <?php if(is_user_logged_in()) { ?>
            <?php if (!get_user_meta(get_current_user_id(), 'paynoccio_wallet')) { ?>
        <div class="cfps-mb-10 lg:cfps-mb-20">
            <img
                    class="cfps-block !cfps-mx-auto"
                    src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/debit_card_example.png' ?>"
                    alt="" />
        </div>
        <div class="cfps-grid cfps-grid-cols-[40px_1fr] cfps-gap-x-6 cfps-gap-y-12 cfps-mb-10">
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_tickets.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="cfps-h2 cfps-mb-0">Fly on the right day. Always.</h2>
                <p class="cfps-text-base">We will book a ticket and notify you in advance if there is a seat available on the plane. Refund the money for the ticket if you do not fly.</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_dollar.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="cfps-h2 cfps-mb-0">Ultimate cashback</h2>
                <p class="cfps-text-base">Make 3 purchases and get an increased cashback on everything</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_coupon.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="cfps-h2 cfps-mb-0 paynocchio-tab-selector">Pay with bonuses</h2>
                <p class="cfps-text-base">Make 5 purchases and get 500 bonuses that can be spent on flights.</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_magic.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="cfps-h2 cfps-mb-0">Unique card design from AI</h2>
                <p class="cfps-text-base">Our AI will generate your individual unique map design for you.</p>
            </div>
        </div>

                <div class="cfps-flex cfps-justify-center cfps-mb-10">
                    <button id="paynocchio_activation_button"
                            type="button"
                            class="cfps-btn-primary">
                        Activate Kopybara.Pay
                        <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <?php wp_nonce_field( 'paynocchio_ajax_activation', 'ajax-activation-nonce' ); ?>
                </div>
            <?php } else { ?>
                <div class="paynocchio-profile-block paynocchio-blue-badge">
                    <div class="cfps-grid cfps-grid-cols-[1fr_100px_200px_35px] cfps-gap-x-6">
                        <div>
                            Kopybara.Pay
                        </div>
                        <div>
                            $0                        </div>
                        <div>
                            100 bonuses
                        </div>
                        <a class="tab-switcher cfps-cursor-pointer" id="wallet_toggle">
                            <img decoding="async" src="https://woocommerce-1172929-4352337.cloudwaysapps.com/wp-content/plugins/woocommerce-paynocchio/assets/img/arr_r.png">
                        </a>
                    </div>
                </div>
                <div class="cfps-flex cfps-justify-center cfps-my-10">
                    <a href="/<?php echo WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG; ?>" class="cfps-btn-primary">Go to my Wallet</a>
                </div>
            <?php } ?>

            <div class="cfps-pl-10 cfps-pb-6 cfps-text-center">
                <p class="cfps-text-slate-500">I agree to <a href="#" class="cfps-text-slate-500 cfps-underline">Kopybara Terms & Conditions</a> and <a href="#" class="cfps-text-slate-500 cfps-underline">rules of Kopybara.Pay Priority program</a> </p>
            </div>
        <?php } else { ?>
            <?php
            $register_redirect = $attr['register_redirect'] ?? '';
            $login_redirect = $attr['login_redirect'] ?? '';
            echo do_shortcode('[paynocchio_registration_block 
            register_redirect="'.$register_redirect.'" 
            login_redirect="'.$login_redirect.'"]');
            ?>
        <?php } ?>
    </div>
</section>
<?php