import './public.css';

import Modal from './modal'

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

    createWebSocket('872ee190-6075-4081-91f2-5a38240c2240');

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
                    $('#top_up_amount').val('');
                    $('.topUpModal .message').text('Successful TopUp');
                    setTimeout(() => {
                        $('.topUpModal .message').text('')
                    }, 1000)
                    console.log(data)
                }
            }
        })
            .error((error) => console.log(error))
            .always(function() {
                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
                $(evt.target).removeClass('cfps-disabled')
            });
    }

    const setBalance = (balance, bonus) => {
        $('.paynocchio-balance-value').text(balance / 10000);
        $('.paynocchio-bonus-value').text(bonus);
        /*
        $('.paynocchio-balance-value').css('--value', balance);
        $('.paynocchio-bonus-value').css('--value', bonus);
        */
    }

    /**
     * Wallet Balance checker
     */
    const walletBalanceChecker = () => {
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

        const activationButton = $("#paynocchio_activation_button");
        const topUpButton = $("#top_up_button");

        activationButton.click((evt) => activateWallet(evt, '/paynocchio-account-page'))
        topUpButton.click((evt) => topUpWallet(evt, '/paynocchio-account-page'))

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

            walletBalanceChecker()

            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));

            const ans = getParameterByName('ans');

            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }

            activationButton.click((evt) => activateWallet(evt))

        });
        // WOOCOMMERCE CHECKOUT SCRIPT END
        //READY END
    });

})(jQuery);
