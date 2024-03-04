<?php
if (!defined('ABSPATH')) {
    die;
}
?>

<section class="paynocchio">
    <div class="article-body max-w-4xl mx-auto">
        <div class="mb-10 lg:mb-20">
            <img
                    class="block mx-auto"
                    src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/debit_card_example.png' ?>"
                    alt="" />
        </div>
        <div class="grid grid-cols-[40px_1fr] gap-x-6 gap-y-12 mb-10">
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_tickets.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="h2 mb-0">Fly on the right day. Always.</h2>
                <p>We will book a ticket and notify you in advance if there is a seat available on the plane. Refund the money for the ticket if you do not fly.</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_dollar.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="h2 mb-0">Ultimate cashback</h2>
                <p>Make 3 purchases and get an increased cashback on everything</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_coupon.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="h2 mb-0">Pay with bonuses</h2>
                <p>Make 5 purchases and get 500 bonuses that can be spent on flights.</p>
            </div>
            <div>
                <img src="<?php echo plugin_dir_url( WOOCOMMERCE_PAYNOCCHIO_BASENAME ) . 'assets/img/icon_magic.svg' ?>" alt="" />
            </div>
            <div>
                <h2 class="h2 mb-0">Unique card design from AI</h2>
                <p>Our AI will generate your individual unique map design for you.</p>
            </div>
        </div>
        <div class="flex justify-center mb-10">
            <button id="paynocchio_activation_button" type="button" class="btn-primary text-2xl px-3 py-2">Activate Kopybara.Pay</button>
        </div>
    </div>
    <div class="pl-10 pb-6">
        <p class="text-gray-300">I agree to <a href="#" class="text-gray-400">Kopybara Terms & Conditions</a> and <a href="#" class="text-gray-400">rules of Kopybara.Pay Priority program</a> </p>
    </div>

</section>
<?php