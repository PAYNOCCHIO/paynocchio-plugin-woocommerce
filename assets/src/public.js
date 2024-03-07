import './public.css';

(( $ ) => {
    $(document).ready(function() {
        $('a.tab-switcher').click(function() {
            let link = $(this);
            let id = link.get(0).id;
            id = id.replace('_toggle','');

            let elem = jQuery('.paynocchio-' + id + '-body');
            if (!elem.hasClass('visible')) {
                jQuery('.paynocchio-tab-selector a').removeClass('choosen');
                link.addClass('choosen');
                elem.siblings('.paynocchio-tab-body').removeClass('visible').fadeOut('fast', function () {
                    elem.fadeIn('fast').addClass('visible');
                });
            }
        });
    });

    $(document).ready(function() {
        $("a.card-toggle").click(function () {
            $('.paynocchio-card-container .visible').fadeOut('fast',function() {
                $('.paynocchio-card-container > div').toggleClass('visible');
                $('.paynocchio-card-container .visible').fadeIn('fast');
            })
        })
    })

})(jQuery);
