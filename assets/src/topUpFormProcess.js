import './js/setTopUpBonuses'
import setTopUpBonuses from "./js/setTopUpBonuses";

(( $ ) => {
    $(document).ready(function () {

        $('.top-up-variants > a').click(function() {
            let amount = $(this).get(0).id.replace('variant_','');
            $('#top_up_amount').val(amount);
            setTopUpBonuses(amount, 0.1)
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

    });
})(jQuery);