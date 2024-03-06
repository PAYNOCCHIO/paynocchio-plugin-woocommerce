(( $ ) => {

    jQuery(document).on( "updated_checkout", function() {
        $('.form-toggle-a').click(() => {
            $('#paynocchio_auth_block > div.visible').fadeOut('fast',function() {
                $('#paynocchio_auth_block > div:not(.visible)').fadeIn('fast');
                $('#paynocchio_auth_block > div').toggleClass('visible');
            });
        });

        function getParameterByName(name, url = window.location.href) {
            name = name.replace(/[\[\]]/g, '\\$&');
            const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        const ans = getParameterByName('ans');

        if (ans) {
            $('.woocommerce-notices-wrapper:first-child').prepend('<div class="woocommerce-message" role="alert">Registration complete. Please check your email, then visit this page again.</div>')
        }
    });


})( jQuery );