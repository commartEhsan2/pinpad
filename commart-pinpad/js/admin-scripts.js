jQuery(document).ready(function($) {
    // Initialize the color picker
    $('.color-field').wpColorPicker();

    // Prevent default behavior for nav-tab links
    $('.nav-tab').click(function(e) {
        e.preventDefault();
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.tab-content').hide();
        $($(this).attr('href')).show();
    });
});