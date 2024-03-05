<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<section class="paynocchio">
    <div class="article-body cfps-max-w-4xl cfps-mx-auto">
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
                <h2 class="cfps-h2 cfps-mb-0">Pay with bonuses</h2>
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
            <button id="paynocchio_activation_button" type="button" class="cfps-btn-primary">Activate Kopybara.Pay</button>
        <div class="cfps-flex cfps-justify-center cfps-mb-10">
            <button id="paynocchio_activation_button" type="button" class="cfps-btn-primary cfps-text-2xl cfps-px-3 cfps-py-2">
                Activate Kopybara.Pay
            </button>
            <?php wp_nonce_field( 'paynocchio_ajax_activation', 'ajax-activation-nonce' ); ?>
        </div>
    </div>
    <div class="cfps-pl-10 cfps-pb-6 cfps-text-center">
        <p class="cfps-text-slate-500">I agree to <a href="#" class="cfps-text-slate-500 cfps-underline">Kopybara Terms & Conditions</a> and <a href="#" class="cfps-text-slate-500 cfps-underline">rules of Kopybara.Pay Priority program</a> </p>
    </div>

</section>
<?php