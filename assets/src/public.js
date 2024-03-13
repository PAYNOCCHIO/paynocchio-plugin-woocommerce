import './public.css';

import Modal from './modal'
import './topUpFormProcess'

(( $ ) => {

    const createWebSocket = (wallet) => {
        let ws = new WebSocket(`wss://wallet.stage.paynocchio.com/ws/wallet-socket/${wallet}`);

        ws.onmessage = function(event) {
            const data = JSON.parse(JSON.parse(event.data));
            setBalance(data.balance.current, data.rewarding_balance)
        };
        ws.onopen = function(event) {
            let event_map = {
                "event": "get_wallet",
            }
            ws.send(JSON.stringify(event_map));
        };
        ws.onclose = function(event) {
            console.log('closed');
        };
        ws.onerror = function(error) {
            console.error(error);
        };
    }

    function setBalance (balance, bonus) {
        $('.paynocchio-balance-value').text(balance / 10000);
        $('.paynocchio-bonus-value').text(bonus);
    }

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

    /**
     * Wallet Activation function
     * @param evt
     * @param path
     */
    const topUpWallet = (evt) => {
        $(evt.target).addClass('cfps-disabled')

        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');

        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'paynocchio_ajax_top_up',
                'ajax-top-up-nonce': $('#ajax-top-up-nonce').val(),
                'amount': $('#top_up_amount').val(),
            },
            success: function(data){
                if (data.response.status_code === 200){
                    $('.topUpModal .message').text('Success!');
                    setTimeout(() => {
                        $('.topUpModal .message').text('')
                    }, 5000)
                }
            }
        })
            .error((error) => console.log(error))
            .always(function() {
                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
                $(evt.target).removeClass('cfps-disabled')
            });
    }

    /**
     * Withdraw from Wallet
     */

    const withdrawWallet = (evt) => {
        $(evt.target).addClass('cfps-disabled')

        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');

        const amount = parseFloat($('#withdraw_amount').val());
        const current_balance = parseFloat($('#witdrawForm .paynocchio-balance-value').text());

        if (current_balance < amount) {
            $('.withdrawModal .message').text('Sorry, can\'t do ;)');
            $(evt.target).removeClass('cfps-disabled')
            $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
            setTimeout(() => {
                $('.withdrawModal .message').text('')
            }, 2000)
        } else {
            $.ajax({
                url: paynocchio_object.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'paynocchio_ajax_withdraw',
                    'ajax-withdraw-nonce': $('#ajax-withdraw-nonce').val(),
                    'amount' : parseFloat($('#withdraw_amount').val()),
                },
                success: function(data){
                    if (data.response.status_code === 200){
                        $('#withdraw_amount').val('');
                        $('.withdrawModal .message').text('Success!');
                        setTimeout(() => {
                            $('.withdrawModal .message').text('')
                        }, 5000)
                    }
                }
            })
                .error((error) => console.log(error))
                .always(function() {
                    $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
                    $(evt.target).removeClass('cfps-disabled')
                });
        }
    }

    /**
     * Wallet Balance checker
     */
    function initiateWebSocket() {
        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'paynocchio_ajax_get_user_wallet',
            },
            success: function(data){
                if(data.walletId) {
                    createWebSocket(data.walletId)
                }
            }
        })
            .error((error) => console.log(error))
    }
    /**
     * Wallet Balance checker
     */
    function walletBalanceChecker() {
        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'paynocchio_ajax_check_balance',
            },
            success: function(data){
                setBalance(data.response.balance, data.response.bonuses)
            }
        })
            .error((error) => console.log(error))
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
        Modal.initElements();

        initiateWebSocket();

        const activationButton = $("#paynocchio_activation_button");
        const topUpButton = $("#top_up_button");
        const withdrawButton = $("#withdraw_button");

        activationButton.click((evt) => activateWallet(evt))
        topUpButton.click((evt) => topUpWallet(evt))
        withdrawButton.click((evt) => withdrawWallet(evt))

        $('a.tab-switcher').click(function() {
            let link = $(this);
            let id = link.get(0).id;
            id = id.replace('_toggle','');

            let elem = $('.paynocchio-' + id + '-body');
            if (!elem.hasClass('visible')) {
                $('.paynocchio-tab-selector a').removeClass('choosen');
                link.addClass('choosen');
                elem.siblings('.paynocchio-tab-body').removeClass('visible').fadeOut('fast', function () {
                    elem.fadeIn('fast').addClass('visible');
                });
            }
        });

        $('a.card-toggle').click(() => toggleVisibility('.paynocchio-card-container'));

        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));

        // WOOCOMMERCE CHECKOUT SCRIPT
        $(document).on( "updated_checkout", function() {

            const activationButton = $("#paynocchio_activation_button");
            const topUpButton = $("#top_up_button");
            const withdrawButton = $("#withdraw_button");
            const place_orderButton = $('#place_order');

            activationButton.click((evt) => activateWallet(evt))
            topUpButton.click((evt) => topUpWallet(evt))
            withdrawButton.click((evt) => withdrawWallet(evt))

            walletBalanceChecker()
            Modal.initElements();

            initiateWebSocket();

            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));

            const ans = getParameterByName('ans');

            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }

            // Conversion rate value picker
            const value = $('#conversion-value');
            const input = $('#conversion-input');
            value.val(input.val());
            input.on('change', function() {
                value.val(input.val());
               /* let perc = (input.val()-input.attr('min')/(input.attr('max')-input.attr('min'))*100;
                input.css('background','linear-gradient(to right, #3b82f6 ' + perc + '%, #f3f4f6 ' + perc + '%)');*/
            })
            value.on('change', function() {
                input.val(value.val());
                /* let perc = (input.val()-input.attr('min')/(input.attr('max')-input.attr('min'))*100;
                 input.css('background','linear-gradient(to right, #3b82f6 ' + perc + '%, #f3f4f6 ' + perc + '%)');*/
            })

            activationButton.click((evt) => activateWallet(evt))

            //place_orderButton.addClass('cfps-disabled')

        });
        // WOOCOMMERCE CHECKOUT SCRIPT END
        //READY END
    });

})(jQuery);
