jQuery(document).ready(function() {
    jQuery('#airbnb-anywhere').on('click', function() {
        jQuery('.airbnb-container').css('display', 'none');
        jQuery('.search-fields').css('display', 'flex');
    });
    jQuery('#airbnb-anywhere').focusout(function() {
        jQuery('.airbnb-container').css('display', 'flex');
        jQuery('.search-fields').css('display', 'none');
    });
});