
(function( $ ) {
    'use strict';



    $(document).ready(function() {
        console.log(document.getElementById('paynocchio_auth_block'))


        const paynocchio_login_form = $('#paynocchio_auth_login');

        console.log(paynocchio_login_form)

        paynocchio_login_form.submit((e) => {
            e.preventDefault();
            console.log('hello')
        })

        /*paynocchio_login_form.on('submit', function(e){
            $('form#login p.status').show().text(ajax_login_object.loadingmessage);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajax_login_object.ajaxurl,
                data: {
                    'action': 'ajaxlogin', //вызов нашей функции wp_ajax_nopriv_ajaxlogin
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
        });*/

    });

})( jQuery );