import './public.css';

(( $ ) => {


});

function paynocchio_tab_toggle (id) {
    let elem = jQuery('.paynocchio-' + id + '-body');
    if (!elem.hasClass('visible')) {
        jQuery('.paynocchio-tab-selector a').removeClass('choosen');
        jQuery('.paynocchio-tab-selector a.' + id + '_toggle').addClass('choosen');
        elem.siblings('.paynocchio-tab-body').removeClass('visible').fadeOut('fast', function () {
            elem.fadeIn('fast').addClass('visible');
        });
    }
}