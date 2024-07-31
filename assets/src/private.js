import './public.css';

import Modal from './modal'
import './topUpFormProcess'
import setTopUpBonuses from "./js/setTopUpBonuses";
import debounce from "./js/debounce";

(( $ ) => {

    //const PERCENT = 0.1;
    let rewardingRules;
    let commissionPercentage;
    let commissionCoefficient;
    let commissionFixed;
    let conversionRate;

    $.ajax({
        async: false,
        url: paynocchio_object.ajaxurl,
        global: false,
        type: 'GET',
        data: {
            'action': 'paynocchio_ajax_get_env_structure',
        },
        success: function (wallet_info) {
            commissionFixed = wallet_info.response.wallet_fixed_commission;
            commissionPercentage = wallet_info.response.wallet_percentage_commission / 100;
            commissionCoefficient = 1 - commissionPercentage;
            conversionRate = wallet_info.response.structure.bonus_conversion_rate;
            rewardingRules = wallet_info.response.structure.rewarding_group.rewarding_rules;
            console.log(wallet_info);
        }
    });

    /**
     * Adapt rewarding rules to sum values of the same rules
     * @param {array} data
     * @return {*[]}
     */
    const transformRewardingRules = (data) => {
        const result = [];

        if(data) {
            data.forEach(item => {
                let existing = result.find(el =>
                    el.operation_type === item.operation_type &&
                    el.min_amount === item.min_amount &&
                    el.max_amount === item.max_amount
                );

                if (existing) {
                    existing.value += item.value;
                } else {
                    result.push({ ...item });
                }
            });
            return result;
        }
        return null;
    };

    /**
     * Find eligible Operations
     * @param {object} obj
     * @param {number} num
     * @param {string} operationType
     * @return {*}
     */
    const getCurrentRewardRule = (obj, num, operationType) => {
        let totalValue = 0;
        let minAmount = Infinity;
        let maxAmount = -Infinity;
        let value_type;
        let conversion_rate = 1;

        if(obj) {
            obj.forEach(item => {
                if (item.operation_type === operationType && num >= item.min_amount && num <= item.max_amount) {
                    totalValue += item.value;
                    value_type = item.value_type;
                    conversion_rate = item.conversion_rate;
                    if (item.min_amount < minAmount) {
                        minAmount = item.min_amount;
                    }
                    if (item.max_amount > maxAmount) {
                        maxAmount = item.max_amount;
                    }
                }
            });
        }
        return {
            totalValue: value_type === 'percentage' ? totalValue / conversion_rate : totalValue,
            minAmount,
            maxAmount,
            value_type,
            conversion_rate,
        };
    };

    const reducedRules = transformRewardingRules(rewardingRules);

    const calculateReward = (amount, rules, type) => {
        const total_value = getCurrentRewardRule(rules, amount, type).totalValue;
        const value_type = getCurrentRewardRule(rules, amount, type).value_type;
        return value_type === 'percentage' ? Math.floor(amount * (total_value / 100)) : total_value;
    };

    /* Calculating commission amount and bonuses for topup with commission */
    const calculateCommissionAndBonuses = (amount, serv_calculation, operation_type) => {
        if (serv_calculation && serv_calculation === true && operation_type) {
            getStructureCalculation(amount, operation_type);
        } else {
            let sumWithCommission = ( amount * parseFloat(commissionCoefficient) - parseFloat(commissionFixed) );
            sumWithCommission = (Math.round(sumWithCommission * 100) / 100);
            let commission = ( amount * parseFloat(commissionPercentage) + parseFloat(commissionFixed) );
            commission = (Math.round(commission * 100) / 100);
            let withdrawalWithCommission = amount - (amount * parseFloat(commissionPercentage)) - parseFloat(commissionFixed);
            withdrawalWithCommission = (Math.round(withdrawalWithCommission * 100) / 100);
        }
    }



    /*Calculating sum for topup with commission*/
    const calculateSumWithCommission = (sum, api, type) => {
        if (api && api === true && type) {
            getStructureCalculation(sum, type);
        } else {
            let sumWithCommission = ( sum * parseFloat(commissionCoefficient) - parseFloat(commissionFixed) );
            sumWithCommission = (Math.round(sumWithCommission * 100) / 100);
            return sumWithCommission;
        }
    }

    /*Calculating sum for withdrawal with commission*/
    const calculateWithdrawalWithCommission = (sum) => {
        let withdrawalWithCommission = sum - (sum * parseFloat(commissionPercentage)) - parseFloat(commissionFixed);
        withdrawalWithCommission = (Math.round(withdrawalWithCommission * 100) / 100);
        return withdrawalWithCommission;
    }

    const calculateCommission = (sum) => {
        let commission = ( sum * parseFloat(commissionPercentage) + parseFloat(commissionFixed) );
        commission = (Math.round(commission * 100) / 100);
        return commission;
    }

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
        $('.paynocchio-balance-value').attr('data-balance', balance);
        $('.paynocchio-bonus-value').attr('data-bonus', bonus);

        let oldBalance = parseFloat($('.paynocchio-balance-value').first().text());
        let newBalance = parseFloat(balance);

        let oldBonus = parseFloat($('.paynocchio-bonus-value').first().text());
        let newBonus = parseFloat(bonus);

        function animateDigits (type, start, end) {
            let elem = $('.paynocchio-' + type + '-value');

            if (start === end) return;
            let range = end - start;
            let current = start;

            let stepTime = 4; // 4 ms
            let increment = (range / 250); // 250 steps

            let i = 0;
            var timer = setInterval(function() {
                current += increment;
                let echo = parseFloat(current).toFixed(2);
                elem.html(echo);
                i++;
                if (i == 250) {
                    clearInterval(timer);
                    elem.html(end.toFixed(2));
                }
            }, stepTime);
        }

        animateDigits('balance', oldBalance, newBalance);
        animateDigits('bonus', oldBonus, newBonus);
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

        if(!$('#top_up_amount').val()) {
            $('.topUpModal .message').html('Please enter amount!');
            return;
        }
        $(evt.target).addClass('cfps-disabled')

        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');

        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'paynocchio_ajax_top_up',
                'ajax-top-up-nonce': $('#ajax-top-up-nonce').val(),
                'amount': $('#top_up_amount').val(),
                'redirect_url': window.location.href,
            },
            success: function(data){
                if (data.response.status_code === 200){
                    window.location.replace(JSON.parse(data.response.response).schemas.url)
                } else {
                    $('.topUpModal .message').html('An error occurred. Please reload page and try again!');
                }
            }
        })
            .error((error) => alert(error.response.response))
            .always(function() {
                $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
                $(evt.target).removeClass('cfps-disabled')
            });
    }

    /**
     * Withdraw from Wallet
     */

    const withdrawWallet = (evt) => {

        if(!$('#withdraw_amount').val()) {
            $('.withdrawModal .message').html('Please enter amount!');
            return;
        }
        $(evt.target).addClass('cfps-disabled')

        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');

        const amount = parseFloat($('#withdraw_amount').val());
        const current_balance = parseFloat($('#witdrawForm .paynocchio-balance-value').text());

        if (current_balance < amount) {
            $('#withdraw_message').text('Sorry, can\'t do ;)');
            $(evt.target).removeClass('cfps-disabled')
            $(`#${evt.target.id} .cfps-spinner`).addClass('cfps-hidden');
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
                        $('.withdrawModal').delay(1000).fadeOut('fast')
                        $('body').removeClass('paynocchio-modal-open');
                    } else {
                        $('.withdrawModal .message').text('An error occurred. Please reload page and try again!');
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
     * Get Structure Calculation
     * @param evt
     * @param path
     */
    const getStructureCalculation = (amount, operation_type) => {
        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'GET',
            data: {
                'action': 'paynocchio_ajax_get_structure_calculation',
                'amount': parseFloat(amount),
                'operation_type': operation_type,
            },
            success: function(data) {
                console.log(data);
                if (!data.response.error) {
                    let commission = data.response.operations_data[0].commission_amount;
                    let sumWithCommission = data.response.operations_data[0].full_amount;
                    let bonusesToAdd = data.response.operations_data[0].bonuses_amount;

                    if (operation_type == "payment_operation_add_money") {
                        if (bonusesToAdd > 0) {
                            $('#topup_message').html('Your balance will be replenished by $<span id="replenishAmount">' + sumWithCommission + '</span>, commission is $<span id="commissionAmount">' + commission + '</span>. You will get <span id="bonusesCounter">' + bonusesToAdd + '</span> bonuses.');
                        } else {
                            $('#topup_message').html('Your balance will be replenished by $<span id="replenishAmount">' + sumWithCommission + '</span>, commission is $<span id="commissionAmount">' + commission + '</span>.');
                        }
                        $('#topup_message').removeClass('loading');
                    } else if (operation_type == "payment_operation_withdraw") {
                        $('#withdraw_message').html('You will receive a withdrawal for $<span id="withdrawal_amount">' + sumWithCommission + '</span>, commission is $<span id="withdrawal_commission_amount">' + commission + '</span>.');
                        $('#withdraw_message').removeClass('loading');
                    } else if (operation_type == "payment_operation_for_services") {
                        console.log(data.response);
                        $('#paynocchio_payment_bonuses').html('You will get additional ' + bonusesToAdd + ' bonuses for this purchase.');
                        $('#paynocchio_payment_bonuses').removeClass('loading');
                    }
                } else {

                }
            }
        })
            .error((error) => console.log(error));
    }

    /**
     * Set Wallet Status
     * @param evt
     * @param path
     */
    const setWalletStatus = (evt, status) => {
        $(evt.target).addClass('cfps-disabled')

        $(`.paynocchio-profile-actions .cfps-spinner`).removeClass('cfps-hidden');

        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'paynocchio_ajax_set_status',
                'ajax-status-nonce': $('#ajax-status-nonce').val(),
                'status': status,
            },
            success: () => $(window.location.reload())
        })
            .error((error) => alert(error))
            .always(() => $(`.paynocchio-profile-actions .cfps-spinner`).addClass('cfps-hidden'));
    }

    /**
     * Delete Wallet from User meta
     * @param evt
     * @param path
     */
    const deleteWallet = (evt) => {
        $(evt.target).addClass('cfps-disabled')

        $(`#${evt.target.id} .cfps-spinner`).removeClass('cfps-hidden');

        $.ajax({
            url: paynocchio_object.ajaxurl,
            type: 'POST',
            data: {
                'action': 'paynocchio_ajax_delete_wallet',
                'ajax-delete-nonce': $('#ajax-delete-nonce').val(),
            },
            success: (data) => $(window.location.reload())
        })
            .error((error) => console.log(error));
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
                setBalance(data.response.balance, data.response.bonuses)
            }
        })
        .error((error) => console.log(error))
    }

    /**
     * Balance polling
     */
    //setInterval(() => updateWalletBalance(), 1000)

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

    function debounce(func, debounseTime) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), debounseTime);
        };
    }

    const debounceTime = 500;
    const debouncedTopupFunction = debounce((value,balance,card_balance_limit) => {
        if (parseFloat(value) + balance > card_balance_limit) {
            $('#topup_message').html('When replenishing the amount $' + value + ' the balance limit will exceed the set value $' + card_balance_limit);
            $('#top_up_button').attr('disabled','true').addClass('disabled');
            $('#topup_message').removeClass('loading');
        } else {
            if (value < parseFloat($('#top_up_amount').attr('min'))) {
                $('#top_up_button').attr('disabled','true').addClass('disabled');
                $('#topup_message').html('Please enter amount more than minimum replenishment amount.');
                $('#topup_message').removeClass('loading');
            } else {
                $('#top_up_button').removeAttr('disabled').removeClass('disabled');
                calculateCommissionAndBonuses(value, true, 'payment_operation_add_money');
            }
        }
    }, debounceTime);

    const debouncedWithdrawFunction = debounce((value,balance,minWithdrawAmount) => {
        if (parseFloat(value) > balance) {
            $('#withdraw_message').html('Insufficient funds. Please check the wallet balance.');
            $('#withdraw_button').attr('disabled','true').addClass('disabled');
            $('#withdraw_message').removeClass('loading');
        } else {
            if (value < minWithdrawAmount) {
                $('#withdraw_button').attr('disabled','true').addClass('disabled');
                $('#withdraw_message').html('Please enter amount more than minimum withdrawal amount.');
                $('#withdraw_message').removeClass('loading');
            } else {
                $('#withdraw_button').removeAttr('disabled').removeClass('disabled');
                calculateCommissionAndBonuses(value, true, 'payment_operation_withdraw');
            }
        }
    }, debounceTime);

    // Payment calculations
    const debouncedPaymentCalculation = debounce((total, bonuses, conversion_rate) => {
        let max_bonuses;
        let max_bonuses_in_money;
        let discount;
        let newprice;
        let newpricefield = $('#paynocchio_after_discount');
        let discountfield = $('#paynocchio_discount');

        let paynocchio_payment_bonuses = $('#paynocchio_payment_bonuses');

        if(bonuses * conversion_rate < total) {
            max_bonuses = bonuses;
            max_bonuses_in_money = bonuses * conversion_rate;
        } else {
            max_bonuses = total / conversion_rate;
            max_bonuses_in_money = total;
        }

        discount = (max_bonuses * conversion_rate * 100 / total).toFixed(2);
        newprice = (total - max_bonuses_in_money).toFixed(2);

        newpricefield.html(newprice);
        discountfield.html(discount);

        calculateCommissionAndBonuses(newprice, true, 'payment_operation_for_services');

        if (newprice != 0) {
            $('.paynocchio_payment_bonuses').show();
        } else {
            $('.paynocchio_payment_bonuses').hide();
        }

        if (newprice == total) {
            $('.paynocchio_before_discount').hide();
            $('.paynocchio_discount').hide();
        } else {
            $('.paynocchio_before_discount').show();
            $('.paynocchio_discount').show();
        }
    }, debounceTime);

    $(document).ready(function() {
        Modal.initElements();

        //initiateWebSocket();

        const topUpButton = $("#top_up_button");
        const withdrawButton = $("#withdraw_button");
        const suspendButton = $("#suspend_button");
        const activateButton = $("#reactivate_button");
        const blockButton = $("#block_button");
        const deleteButton = $("#delete_button");

        topUpButton.click((evt) => topUpWallet(evt))
        withdrawButton.click((evt) => withdrawWallet(evt))
        suspendButton.click((evt) => setWalletStatus(evt, 'SUSPEND'))
        activateButton.click((evt) => setWalletStatus(evt, 'ACTIVE'))
        blockButton.click((evt) => setWalletStatus(evt, 'BLOCKED'))
        deleteButton.click((evt) => deleteWallet(evt))

        /**
         * Trigger update checkout when payment method changed
         */
        $('form.checkout').on('change', 'input[name="payment_method"]', function(){
            $(document.body).trigger('update_checkout');
        });

        $('#top_up_amount').on('keyup change', (evt) => {
            let card_balance_limit = parseFloat($('#card_balance_limit').text());
            let balance = parseFloat($('.paynocchio-balance-value').first().text());
            let value = parseFloat(evt.target.value);
            $('#topup_message').addClass('loading');
            debouncedTopupFunction(value,balance,card_balance_limit);
        })

        $('.top-up-variants > a').click(function() {
            let amount = $(this).get(0).id.replace('variant_','');
            $('#top_up_amount').val(amount);
            $('#topup_message').addClass('loading');
            calculateCommissionAndBonuses(amount, true, 'payment_operation_add_money');
        });

        $('#withdraw_amount').on('keyup change', (evt) => {
            let minWithdrawAmount = 1;
            let balance = parseFloat($('.paynocchio-balance-value').first().text());
            let value = parseFloat(evt.target.value);
            $('#withdraw_message').addClass('loading');
            debouncedWithdrawFunction(value,balance,minWithdrawAmount);
        })

        /**
         * WOOCOMMERCE CHECKOUT SCRIPT
         */

        $(document).on( "updated_checkout", function() {

            Modal.initElements();

            const topUpButton = $("#top_up_button");
            //const withdrawButton = $("#withdraw_button");

            topUpButton.click((evt) => topUpWallet(evt))
            //withdrawButton.click((evt) => withdrawWallet(evt))

            updateWalletBalance();

            // Registration complete congratz //
            const ans = getParameterByName('ans');
            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }

            // Topup calculations //
            $('#top_up_amount').on('keyup change', (evt) => {
                let card_balance_limit = parseFloat($('#card_balance_limit').text());
                let balance = parseFloat($('.paynocchio-balance-value').first().text());
                let value = parseFloat(evt.target.value);
                $('#topup_message').addClass('loading');
                debouncedTopupFunction(value,balance,card_balance_limit);
            })
            $('.top-up-variants > a').click(function() {
                let amount = $(this).get(0).id.replace('variant_','');
                $('#top_up_amount').val(amount);
                $('#topup_message').addClass('loading');
                calculateCommissionAndBonuses(amount, true, 'payment_operation_add_money');
            });

            // Conversion rate value picker
            const value = $('#bonuses-value');
            const input = $('#bonuses-input');
            const order_total = parseFloat($('.woocommerce-Price-amount').text().replace('$', ''));
            value.val(input.val());

            $(document).ready(function () {
                $('#paynocchio_payment_bonuses').addClass('loading');
                debouncedPaymentCalculation(parseFloat(order_total), input.val(), parseFloat(conversionRate));
            });

            input.on('change', function() {
                value.val(input.val());
                $('#paynocchio_payment_bonuses').addClass('loading');
                debouncedPaymentCalculation(parseFloat(order_total), input.val(), parseFloat(conversionRate));
                checkBalance();
            })
            value.on('change', function() {
                input.val(value.val());
                $('#paynocchio_payment_bonuses').addClass('loading');
                debouncedPaymentCalculation(parseFloat(order_total), input.val(), parseFloat(conversionRate));
                checkBalance();
            })
            $('input[type=range]').on('input', function () {
                $(this).trigger('change');
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

            /**
             * Hide Place order button if no money
             * @type {define.amd.jQuery|HTMLElement|*}
             */
            function checkBalance() {
                const place_orderButton = $('#place_order');
                const hidden = ($('.payment_box.payment_method_paynocchio').is(":hidden"));

                if(place_orderButton && !hidden) {
                    const balance_value = parseFloat($('.paynocchio-balance-value').first().attr('data-balance'));
                    const bonus_value = parseFloat($('.paynocchio-bonus-value').first().attr('data-bonus'));
                    const order_total = parseFloat($('.woocommerce-Price-amount').text().replace('$', ''));
                    const inputed_bonuses_value = parseFloat($('#bonuses-value').val());

                    if ((balance_value + bonus_value * conversionRate) < order_total) {
                        place_orderButton.addClass('cfps-disabled')
                        place_orderButton.text('Please TopUp your Wallet')
                    } else if ((balance_value + bonus_value * conversionRate) >= order_total && (inputed_bonuses_value * conversionRate + balance_value) < order_total) {
                        place_orderButton.addClass('cfps-disabled')
                        place_orderButton.text('Please TopUp your Wallet or use your Bonuses')
                    } else {
                        place_orderButton.removeClass('cfps-disabled')
                        place_orderButton.text('Place order')
                    }
                }
            }

            checkBalance();

            $("#bonuses-value").on("change", function() {
                checkBalance();
            });

            $('input[type="range"].slider-progress').each(function() {
                $(this).css('--value', $(this).val());
                $(this).css('--min', $(this).attr('min') == '' ? '0' : $(this).attr('min'));
                $(this).css('--max', $(this).attr('max') == '' ? '0' : $(this).attr('max'));
                $(this).on('input', function () {
                    $(this).css('--value', $(this).val())
                    $( "#bonuses-value" ).trigger( "change" );
                });
            });

            $('#top_up_amount').on('keyup change', (evt) => {
                let reward = 0;
                let value = parseFloat(evt.target.value);
                if (calculateReward(value, reducedRules, 'payment_operation_add_money') > 0) {
                    reward = calculateReward(value, reducedRules, 'payment_operation_add_money');
                }
                let replenishAmount = calculateSumWithCommission(value);
                let commissionAmount = calculateCommission(value);
                setTopUpBonuses(evt.target.value, reward, replenishAmount, commissionAmount);

                let card_balance_limit = parseFloat($('#card_balance_limit').text());
                let balance = parseFloat($('.paynocchio-balance-value').first().text());

                if (parseFloat(evt.target.value) + balance > card_balance_limit) {
                    $('#topup_message').html('When replenishing the amount $' + value + ' the balance limit will exceed the set value $' + card_balance_limit);
                    $('#top_up_button').attr('disabled','true').addClass('disabled');
                } else {
                    if (evt.target.value < parseFloat($('#top_up_amount').attr('min'))) {
                        $('#top_up_button').attr('disabled','true').addClass('disabled');
                        $('#topup_message').html('Please enter amount more than minimum replenishment amount.');
                    } else {
                        $('#top_up_button').removeAttr('disabled').removeClass('disabled');
                        if (reward > 0) {
                            $('#topup_message').html('Your balance will be replenished by $<span id="replenishAmount">' + replenishAmount + '</span>, commission is $<span id="commissionAmount">' + commissionAmount + '</span>. You will get <span id="bonusesCounter">' + reward + '</span> bonuses.');
                        } else {
                            $('#topup_message').html('Your balance will be replenished by $<span id="replenishAmount">' + replenishAmount + '</span>, commission is $<span id="commissionAmount">' + commissionAmount + '</span>.');
                        }
                    }
                }
            })

        });
        // WOOCOMMERCE CHECKOUT SCRIPT END

        $('#show_mini_modal').on('click', function() {
            $('.topup_mini_form').toggle();
            $(this).toggleClass('active');
            if ($(this).hasClass('active')) {
                $(this).css('transform','rotate(45deg)');
            } else {
                $(this).css('transform','rotate(0deg)');
            }
        });
    });
    // READY END

})(jQuery);
