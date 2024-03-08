(( $ ) => {

    $(document).ready(() => {

        const toggleVisibility = (blockClass) => {
            $(`${blockClass} > div.visible`).fadeOut('fast',function() {
                $(`${blockClass} > div:not(.visible)`).fadeIn('fast');
                $(`${blockClass} > div`).toggleClass('visible');
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

        $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));

        // WOOCOMMERCE CHECKOUT SCRIPT
        $(document).on( "updated_checkout", function() {
            $('.form-toggle-a').click(() => toggleVisibility('#paynocchio_auth_block'));

            const ans = getParameterByName('ans');

            if (ans) {
                $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
            }

            const activation_button = $("#paynocchio_activation_button");

            activation_button.click(() => {
console.log('click')
                $.ajax({
                    url: paynocchio_activation_object.ajaxurl,
                    type: 'POST',
                    data: {
                        'action': 'paynocchio_ajax_activation',
                        'source': window.location.pathname,
                        'ajax-activation-nonce': $('#ajax-activation-nonce').val(),
                    },
                    success: function(data){
                        if (data.success){
                            document.location.reload();
                        }
                    }
                });
            })

        });
    })

})( jQuery );