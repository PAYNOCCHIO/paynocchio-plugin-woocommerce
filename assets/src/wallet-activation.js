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
            $('#register_messages').show().removeClass('success').text('Email and login are required');
        } else {
            $('#login_messages').hide();
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
                        $('#register_messages').show().addClass('success').html("Please check your email to confirm registration.");
                    } else {
                        if(data.data.message === "Sorry, that email address is already used!") {
                            $('#register_messages').show().removeClass('success').html("Sorry, this email address is already registered. Please <a style='color:#0c88b4' href='/account'>log in</a> or <a style='color:#0c88b4' href='/account'>restore password</a>. For any case please <a style='color:#0c88b4' href='mailto:support@airticket-demo.com'>contact support</a>.</p>");
                        } else {
                            $('#register_messages').show().removeClass('success').text(data.data.message);
                        }
                    }
                },
                error: (error) => console.log(error),
            })
        }
    }

    const loginaction = (evt) => {
        evt.preventDefault();
        if(!$('#user_login').val() && !$('#user_pass').val()) {
            $('#login_messages').show().text('Login and password are required');
        } else {
            $('#login_messages').hide();
            $.ajax({
                url: paynocchio_object.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'paynocchio_ajax_login',
                    'nonce': $('#loginsecurity').val(),
                    'password': $('#user_pass').val(),
                    'username': $('#user_name').val(),
                    'remember': $('#paynocchio_rememberme').val(),
                },
                success: function(data){
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.loggedin == true){
                        $('#login_messages').show().addClass('success').text(data.message);
                        document.location.href = '/checkout/?step=2';
                    } else {
                        $('#login_messages').show().text(data.message);
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
                    $('#response_message').text('Activation processing');
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

    function togglePwd (evt) {
        let field = $('#user_pass');
        if (field.hasClass('shown')){
            $('#user_pass').attr('type', 'password').removeClass('shown');
            $('#show_password').attr('title', 'Show Password').find('span').removeClass('dashicons-hidden').addClass('dashicons-visibility');
        } else {
            $('#user_pass').attr('type', 'text').addClass('shown');
            $('#show_password').attr('title', 'Hide Password').find('span').addClass('dashicons-hidden').removeClass('dashicons-visibility');
        }
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
        $('#paynocchio_wp-submit').click((evt) => loginaction(evt));
        $('#show_password').click((evt) => togglePwd(evt));

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
            $('#paynocchio_wp-submit').click((evt) => loginaction(evt));
            $('#show_password').click((evt) => togglePwd(evt));

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

            activationButton.click((evt) => activateWallet(evt, '/checkout/?step=2'))

            $('.form-toggle-a').click(() => toggleVisibility('#login-signup-forms'));

            const ans = getParameterByName('ans');

            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }


        });
        // WOOCOMMERCE CHECKOUT SCRIPT END
        //READY END
    });

    jQuery(document).ready(function($) {

        // Show the login dialog box on click
        $('a#show_login').on('click', function(e){
            $('body').prepend('<div class="login_overlay"></div>');
            $('form#login').fadeIn(500);
            $('div.login_overlay, form#login a.close').on('click', function(){
                $('div.login_overlay').remove();
                $('form#login').hide();
            });
            e.preventDefault();
        });

        // Perform AJAX login on form submit
        $('form#login').on('submit', function(e){
            $('form#login p.status').show().text(ajax_login_object.loadingmessage);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_login_object.ajaxurl,
                data: {
                    'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                    'username': $('form#login #username').val(),
                    'password': $('form#login #password').val(),
                    'security': $('form#login #security').val() },
                success: function(data){
                    $('form#login p.status').text(data.message);
                    if (data.loggedin == true){
                        document.location.href = ajax_login_object.redirecturl;
                    }
                }
            });
            e.preventDefault();
        });

    });

})(jQuery);
