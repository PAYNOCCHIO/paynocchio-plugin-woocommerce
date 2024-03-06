import './public.css';

(( $ ) => {
    $(document).ready(function() {
        $("a.tab-switcher").click(function() {
            console.log('switch');
            let link = $(this);
            let id = link.get(0).id;
            id = id.replace('_toggle','');

            console.log(id);

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
})(jQuery);
