import './public.css';

(( $ ) => {

    const toggleVisibility = (blockClass) => {
        $(`${blockClass} > div.visible`).fadeOut('fast',function() {
            $(`${blockClass} > div:not(.visible)`).fadeIn('fast');
            $(`${blockClass} > div`).toggleClass('visible');
        });
    }

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

        $("#paynocchio_activation_button").click((evt) => activateWallet(evt, '/paynocchio-account-page'))

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

        $("a.card-toggle").click(function () {
            $('.paynocchio-card-container .visible').fadeOut('fast',function() {
                $('.paynocchio-card-container > div').toggleClass('visible');
                $('.paynocchio-card-container .visible').fadeIn('fast');
            });
        });

        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));
        //READY END
    });


    // WOOCOMMERCE CHECKOUT SCRIPT
    $(document).on( "updated_checkout", function() {
        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));

        const ans = getParameterByName('ans');

        if (ans) {
            $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
        }

        $("#paynocchio_activation_button").click(() => activateWallet())

    });

})(jQuery);
