
(function( $ ) {

    $(document).ready(function() {

        const activation_button = $("#paynocchio_activation_button");

        activation_button.click(() => {

            /*$.ajax({
                url: 'https://wallet.stage.paynocchio.com/wallet/',
                type: 'POST',
                headers: {
                    'X-Wallet-Signature': '67c96d2e879bb762adbc63d3a85524b026dbd7cbad0a481155c4b9bf9ea98b6f',
                    'X-API-KEY': 'X-API-KEY',
                    'Content-Type':'application/json'
                },
                data: {
                    "user_uuid": "ff01e250-2ad5-4e8e-a8ec-2927670ebe65",
                    "environment_uuid": "2985465d-ce65-4257-8937-367e5c094432",
                    "currency_uuid": "970d83de-1dce-47bd-a45b-bb92bf6df964",
                    "type_uuid": "93ac9017-4960-41bf-be6d-aa123884451d",
                    "status_uuid": "ae1b841f-2e56-4fb9-a935-2064304f8639"
                },
                success: function(data){
                    console.log(data)
                    if (data){
                        //document.location.href = data.redirecturl;
                    }
                }
            });*/

            $.ajax({
                url: paynocchio_activation_object.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'paynocchio_ajax_activation',
                    'source': window.location.pathname,
                    'ajax-activation-nonce': $('#ajax-activation-nonce').val(),
                },
                success: function(data){
                    console.log(data)
                    if (data){
                        //document.location.href = data.redirecturl;
                    }
                }
            });
        })

    });

})( jQuery );