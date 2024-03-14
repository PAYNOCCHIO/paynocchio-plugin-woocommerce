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
        $('.paynocchio-balance-value').text('');
        $('.paynocchio-bonus-value').text('');
        $('.paynocchio-balance-value').text(balance);
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
                    updateWalletBalance();
                    updateOrderButtonState();
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
                        updateWalletBalance();
                        updateOrderButtonState();
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
    function updateWalletBalance() {
        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'paynocchio_ajax_check_balance',
            },
            success: function(data){
                //console.log(data.response.balance)
                setBalance(data.response.balance, data.response.bonuses)
            }
        })
            .error((error) => console.log(error))
    }

    /**
     * Balance polling
     */
    //setInterval(() => updateWalletBalance(), 5000)

    function updateOrderButtonState() {
        const place_orderButton = $('#place_order');
        const hidden = ($('.payment_box.payment_method_paynocchio').is(":hidden"));
        if(place_orderButton && !hidden) {
            $(document.body).trigger('update_checkout');
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
        Modal.initElements();

        //initiateWebSocket();

        const topUpButton = $("#top_up_button");
        const withdrawButton = $("#withdraw_button");

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

        /**
         * Trigger update checkout when payment method changed
         */
        $('form.checkout').on('change', 'input[name="payment_method"]', function(){
            $(document.body).trigger('update_checkout');
        });

        // WOOCOMMERCE CHECKOUT SCRIPT
        $(document).on( "updated_checkout", function() {
            const topUpButton = $("#top_up_button");
            const withdrawButton = $("#withdraw_button");

            topUpButton.click((evt) => topUpWallet(evt))
            withdrawButton.click((evt) => withdrawWallet(evt))

            updateWalletBalance()
            Modal.initElements();

            const ans = getParameterByName('ans');

            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }

            // Conversion rate value picker
            const value = $('#bonuses-value');
            const input = $('#bonuses-input');
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
            $('input[type=range]').on('input', function () {
                $(this).trigger('change');
            });

            $('.top-up-variants > a').click(function() {
                let amount = $(this).get(0).id.replace('variant_','');
                $('#top_up_amount').val(amount);
            });

            $('.toggle-autodeposit').click(function () {
                $(this).toggleClass('checked');
                if ($(this).hasClass('checked')) {
                    $('input#autodeposit').attr('value','1');
                } else {
                    $('input#autodeposit').attr('value','0');
                };
            });

            //$('#source-card').attr('value',$('.current-card').id);

            $('.card-var').click(function () {
                $('.card-variants').toggleClass('clicked');
                $('.clicked .card-var').click(function() {
                    $('.card-var').removeClass('current-card');
                    $(this).addClass('current-card');
                    $('#source-card').attr('value',$(this).attr('data-pan'));
                });

            });

            const place_orderButton = $('#place_order');
            const hidden = ($('.payment_box.payment_method_paynocchio').is(":hidden"));

            if(place_orderButton && !hidden) {
                const balance_value = parseFloat($('.paynocchio-card-simulator .paynocchio-balance-value').text());
                const bonus_value = parseFloat($('.paynocchio-card-simulator .paynocchio-bonus-value').text());
                const order_total = parseFloat($('.order-total .woocommerce-Price-amount').text().replace('$', ''))
                if( (balance_value + bonus_value) < order_total) {
                    place_orderButton.addClass('cfps-disabled')
                    place_orderButton.text('Please TopUp your Wallet')
                }
            }

        });
        // WOOCOMMERCE CHECKOUT SCRIPT END
        //READY END
    });

})(jQuery);
