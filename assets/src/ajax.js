
(function( $ ) {

    $(document).ready(function() {

        jQuery(document).on( "updated_checkout", function(){

            $('#place_order').addClass('disabled');

            $('#paynocchio_login_button').click(function (e) {

                e.preventDefault();

                $.ajax({
                    url: paynocchio_login_object.ajaxurl,
                    type: 'POST',
                    data: {
                        'action': 'paynocchio_ajax_login',
                        'username': $('#paynocchio_login_username').val(),
                        'password': $('#paynocchio_login_password').val(),
                        'ajax-login-nonce': $('#paynocchio_login_password').val() },
                    success: function(data){
                        if (data.loggedin == true){
                            document.location.href = paynocchio_login_object.redirecturl;
                        }
                    }
                });
            })
        });

    });

})( jQuery );