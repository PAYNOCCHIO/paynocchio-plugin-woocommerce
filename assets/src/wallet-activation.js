import './public.css';


(( $ ) => {

    /**
     * Function to make block visibility work
     * @param blockClass
     */
    const toggleVisibility = (blockClass) => {
        $(`${blockClass} > div.visible`).fadeOut('fast',function() {
            $(`${blockClass} > div:not(.visible)`).fadeIn('fast');
            $(`${blockClass} > div`).toggleClass('visible');
        });
    }

    /**
     * Wallet Activation function
     * @param evt
     * @param path
     */
    const activateWallet = (evt, path) => {
        $(evt.target).addClass('cfps-disabled')

        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');

        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'paynocchio_ajax_activation',
                'ajax-activation-nonce': $('#ajax-activation-nonce').val(),
            },
            success: function(data){
                if (data.success){
                    path ? document.location.href = path : document.location.reload();
                }
            }
        })
            .always(function() {
                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
                $(evt.target).removeClass('cfps-disabled')
            });
    }


    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    $(document).ready(function() {
        //READY START

        const checkout = window.location.pathname === '/checkout/';

        const activationButton = $("#paynocchio_activation_button");

        if(!checkout) {
            activationButton.click((evt) => activateWallet(evt))
        }

        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));

        /**
         * Trigger update checkout when payment method changed
         */
        $('form.checkout').on('change', 'input[name="payment_method"]', function(){
            $(document.body).trigger('update_checkout');
        });
        // WOOCOMMERCE CHECKOUT SCRIPT
        $(document).on( "updated_checkout", function() {
            const paynocchio_auth_block = $('#paynocchio_auth_block').is(":hidden")
            const place_orderButton = $('#place_order');

            const payment_hidden = ($('.payment_box.payment_method_paynocchio').is(":hidden"));
            const activatioin_block_hidden = ($('.payment_box.payment_method_paynocchio .paynocchio').is(":hidden"));

            if(place_orderButton && !payment_hidden) {
                if(paynocchio_auth_block) {
                    place_orderButton.addClass('cfps-disabled')
                    place_orderButton.text('Please login or register')
                }
                if(!activatioin_block_hidden) {
                    place_orderButton.addClass('cfps-disabled')
                    place_orderButton.text('Please activate the Wallet first')
                }
            }



            const activationButton = $("#paynocchio_activation_button");

            activationButton.click((evt) => activateWallet(evt))


            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));

            const ans = getParameterByName('ans');

            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }


        });
        // WOOCOMMERCE CHECKOUT SCRIPT END
        //READY END
    });

})(jQuery);
