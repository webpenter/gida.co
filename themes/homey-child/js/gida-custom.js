jQuery(document).ready(function() {
    jQuery('#airbnb-anywhere').on('click', function() {
        jQuery('.airbnb-container').hide();
        jQuery('.search-fields').show().animate({height: "70px", width: "800px"});
        jQuery('.search-fields .search-destination button').addClass('airbnb-active');
        jQuery('.search-fields .search-destination button').click(function() {
            jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');
        });
    });

    jQuery(".main-search-date-range-js input").on('focus', function() {
        jQuery('.search-fields .search-destination button').removeClass('airbnb-active');
        focusedInput = jQuery(this).attr('name');
        if(focusedInput == "arrive"){
            jQuery('.search-date-range-depart input').removeClass('airbnb-active');
            jQuery(this).addClass("airbnb-active");
        } else {
            jQuery('.search-date-range-arrive input').removeClass('airbnb-active');
            jQuery(this).addClass("airbnb-active");
        }
    });

    jQuery('.search-fields .search-guests input').on('focus', function() {
        jQuery('.search-date-range-depart input').removeClass('airbnb-active');
        jQuery(this).addClass("airbnb-active");
    });

});