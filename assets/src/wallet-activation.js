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
     * Register function
     */
    const register = (evt) => {
        evt.preventDefault();
        if(!$('#user_login').val() && !$('#user_email').val()) {
            $('#register_messages').text('Email and login are required');
        } else {
            $.ajax({
                url: paynocchio_object.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'paynocchio_ajax_registration',
                    'ajax-registration-nonce': $('#ajax-registration-nonce').val(),
                    'login': $('#user_login').val(),
                    'email': $('#user_email').val(),
                },
                success: function(data){
                    console.log(data)
                    if(data.success) {
                        $('#register_messages').text('Please check your email to confirm registration.')
                    } else {
                        if(data.data.message === "Sorry, that email address is already used!") {
                            $('#register_messages').html("<p>Sorry, this email address is already registered.</p>" +
                                "<p>Please <a style='color:#0c88b4' href='/account'>log in</a> or <a style='color:#0c88b4' href='/account'>restore password</a>.</p>" +
                                "<p>For any case please <a style='color:#0c88b4' href='mailto:support@kopybara.com'>contact support</a>.</p>" + ""
                            );
                        } else {
                            $('#register_messages').text(data.data.message);
                        }
                    }
                },
                error: (error) => console.log(error),
            })
        }
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
                    $('#response_message').text('Success');
                    path ? document.location.href = path : document.location.reload();
                } else {
                    $('#response_message').text(JSON.parse(data.response.response).detail);
                }
            }
        })
            .always(function() {
                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
                $(evt.target).removeClass('cfps-disabled');
            })
            .error(error => console.log(error.response));
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

        $('#wp-submit-registration').click((evt) => register(evt));

        const checkout = window.location.pathname === '/checkout/?activated=1';

        const activationButton = $("#paynocchio_activation_button");

        if(!checkout) {
            activationButton.click((evt) => activateWallet(evt))
        }

        $('.form-toggle-a').click(() => toggleVisibility('#login-signup-forms'));

        /**
         * Trigger update checkout when payment method changed
         */
        $('form.checkout').on('change', 'input[name="payment_method"]', function(){
            $(document.body).trigger('update_checkout');
        });
        // WOOCOMMERCE CHECKOUT SCRIPT
        $(document).on( "updated_checkout", function() {

            $('#wp-submit-registration').click((evt) => register(evt));


            const paynocchio_auth_block = $('#paynocchio_auth_block').length
            const place_orderButton = $('#place_order');

            const payment_hidden = ($('.payment_box.payment_method_paynocchio').is(":hidden"));
            const activation_block = $('.payment_box.payment_method_paynocchio .paynocchio').length;

            if(place_orderButton && !payment_hidden) {
                if(activation_block) {
                    place_orderButton.addClass('cfps-disabled')
                    place_orderButton.text('Please activate the Wallet first')
                }
                if(paynocchio_auth_block) {
                    place_orderButton.addClass('cfps-disabled')
                    place_orderButton.text('Please login or register')
                }
            }

            const activationButton = $("#paynocchio_activation_button");

            activationButton.click((evt) => activateWallet(evt))

            $('.form-toggle-a').click(() => toggleVisibility('#login-signup-forms'));

            const ans = getParameterByName('ans');

            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }


        });
        // WOOCOMMERCE CHECKOUT SCRIPT END
        //READY END
    });

})(jQuery);
