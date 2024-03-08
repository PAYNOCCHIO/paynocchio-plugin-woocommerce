
(function( $ ) {

    $(document).ready(function() {

        const activation_button = $("#paynocchio_activation_button");

        activation_button.click(() => {

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
                        document.location.href = '/paynocchio-account-page';
                    }
                }
            });
        })

    });

})( jQuery );