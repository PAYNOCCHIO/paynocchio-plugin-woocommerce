<?php
if (!defined('ABSPATH')) {
    die;
}

$wallet_bal = 0;
?>

    <section class="paynocchio">
        <div class="article-body cfps-max-w-4xl cfps-mx-auto cfps-mt-4">
            <div class="cfps-my-10">
                <img
                    class="cfps-block !cfps-mx-auto !cfps-w-full !cfps-max-w-[350px]"
                    src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/card.webp' ?>"
                    alt="" />
            </div>
            <div class="cfps-grid cfps-grid-cols-[1fr_1fr] cfps-gap-x-6 cfps-gap-y-12 cfps-mb-10 cfps-p-8 cfps-items-top">
                <div class="cfps-grid cfps-grid-cols-[50px_1fr] cfps-gap-6 cfps-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_welcome.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h2 class="cfps-h2 cfps-mb-0">Welcome bonus</h2>
                        <p class="cfps-text-base">Earn 75 bonuses for signing up.</p>
                    </div>
                </div>
                <div class="cfps-grid cfps-grid-cols-[50px_1fr] cfps-gap-6 cfps-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_buy.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h2 class="cfps-h2 cfps-mb-0">Buy the ticket</h2>
                        <p class="cfps-text-base">Earn 1 bonus for every 1$ spent.</p>
                    </div>
                </div>
                <div class="cfps-grid cfps-grid-cols-[50px_1fr] cfps-gap-6 cfps-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_birthday.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h2 class="cfps-h2 cfps-mb-0">Birthday bonus</h2>
                        <p class="cfps-text-base">Earn bonuses to celebrate your birthday.</p>
                    </div>
                </div>
                <div class="cfps-grid cfps-grid-cols-[50px_1fr] cfps-gap-6 cfps-items-top">
                    <div>
                        <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_topup.svg' ?>" alt="" />
                    </div>
                    <div>
                        <h2 class="cfps-h2 cfps-mb-0">Add money</h2>
                        <p class="cfps-text-base">Earn an additional bonuses for each adding a money to wallet.</p>
                    </div>
                </div>
            </div>
            <?php if(is_user_logged_in()) { ?>
                <?php if (!get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY)) { ?>
                    <div class="cfps-flex cfps-justify-center cfps-mb-10">
                        <button id="paynocchio_activation_button"
                                type="button"
                                class="cfps-btn-primary cfps-rounded-lg">
                            Activate Paynocchio.Pay
                            <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        <?php wp_nonce_field( 'paynocchio_ajax_activation', 'ajax-activation-nonce' ); ?>
                    </div>
                <?php } else { ?>
                    <div class="cfps-flex cfps-justify-center cfps-my-10">
                        <a href="/<?php echo WOOCOMMERCE_PAYNOCCHIO_ACCOUNT_PAGE_SLUG; ?>" class="cfps-btn-primary">Go to Paynocchio.Pay</a>
                    </div>
                <?php } ?>

                <div class="cfps-pl-10 cfps-pb-6 cfps-text-center">
                    <p class="cfps-text-slate-500">I agree to <a href="#" class="cfps-text-slate-500 cfps-underline">Terms & Conditions</a> and <a href="#" class="cfps-text-slate-500 cfps-underline">Rules of Priority program</a> </p>
                </div>
            <?php } else { ?>
                <button id="paynocchio_anonimous_activation_button"
                        type="button"
                        class="cfps-btn-primary">
                    Activate Paynocchio.Pay
                    <svg class="cfps-spinner cfps-hidden cfps-animate-spin cfps-ml-4 cfps-h-5 cfps-w-5 cfps-text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="cfps-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="cfps-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <input id="is_paynocchio_user_exist" type="hidden" name="is_paynocchio_user_exist" value="false" />
                <?php wp_nonce_field( 'paynocchio_ajax_activation', 'ajax-activation-nonce' ); ?>

                <div class="paynocchio-payment-block" id="paynocchio-payment-block" style="display: none">
                    <div class="cfps-grid cfps-grid-cols-[1fr_1fr] cfps-gap-x-8 cfps-items-stretch">
                        <div class="paynocchio-card-simulator">
                            <h3 class="!cfps-mb-0">Paynocchio.Pay</h3>
                            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-text-white cfps-gap-x-8 cfps-text-xl">
                                <div>
                                    <p>Balance</p>
                                    <p>$<span class="paynocchio-numbers paynocchio-balance-value"><?php echo $wallet['balance'] ?? 0; ?></span></p>
                                </div>
                                <div>
                                    <p>Bonuses</p>
                                    <p><span class="paynocchio-numbers paynocchio-bonus-value"><?php echo $wallet['bonuses'] ?? 0 ?></span></p>
                                </div>
                            </div>

                            <div class="cfps-flex cfps-flex-row cfps-items-center cfps-gap-x-2">
                                <a href="#" class="btn-blue" data-modal=".topUpModal">Add money</a>
                                <a href="#" class="btn-white" data-modal=".withdrawModal">Withdraw</a>
                            </div>
                        </div>
                        <div class="paynocchio-promo-badge">
                            <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/cashback_ill.png' ?>" class="cfps-max-h-full cfps-float-right" />
                            <h3 class="!cfps-mb-0">Ultimate Cashback</h3>
                            <p>Make three purchases and get an increased cashback on everything!</p>
                            <a class="btn-white cfps-absolute cfps-bottom-4 cfps-left-4" href="#">Read more</a>
                        </div>
                    </div>
                    <?php if($wallet['bonuses']) {
                        if($wallet['bonuses'] < $cart_total) {
                            $max_bonus = $wallet['bonuses'];
                        } else {
                            $max_bonus = $cart_total;
                        }
                        ?>
                        <div class="paynocchio-conversion-rate cfps-mt-8">
                            <h3>
                                How much do you want to pay in bonuses?
                            </h3>
                            <?php
                            woocommerce_form_field( 'bonusesvalue', [
                                'type'        => 'number',
                                'id'          => 'bonuses-value',
                                'label'       => '',
                                'placeholder' => '',
                                'default'     => '',
                                'input_class' => ['short'],
                            ] );
                            ?>
                            <input id="bonuses-input" type="range" min="0" max="<?php echo $max_bonus; ?>" step="1" value="0" class="styled-slider slider-progress" />

                        </div>
                    <?php } ?>
                </div>
                <?php
                /*$register_redirect = $attr['register_redirect'] ?? '';
                $login_redirect = $attr['login_redirect'] ?? '';
                echo do_shortcode('[paynocchio_registration_block
                register_redirect="'.$register_redirect.'"
                login_redirect="'.$login_redirect.'"]');*/
                ?>
            <?php } ?>
        </div>
    </section>
<?php