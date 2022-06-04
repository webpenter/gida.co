jQuery(document).ready(function() {
    jQuery('#airbnb-anywhere').on('click', function() {
        jQuery('.airbnb-container').hide(500);
        jQuery('.search-fields').show(500).animate({height: "70px", width: "800px"});
        jQuery('.search-fields .search-destination button').addClass('airbnb-active');
        jQuery('.search-fields .search-destination button span.filter-option').addClass('text-bold ').css({"left": "20px"});
        jQuery('.search-fields .search-destination button').click();
    });

    jQuery('.search-fields .search-destination button').click(function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.search-fields .search-guests input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery(this).addClass("airbnb-active");
    });

    jQuery(".main-search-date-range-js input").on('focus', function() {
        jQuery('.search-fields .search-guests input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-fields .search-destination button').removeClass('airbnb-active');
        focusedInput = jQuery(this).attr('name');
        if(focusedInput == "arrive"){
            jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
            jQuery(this).addClass("airbnb-active").parent().addClass('z-index');
        } else {
            jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
            jQuery(this).addClass("airbnb-active").parent().addClass('z-index');
        }
    });

    jQuery('.search-fields .search-guests input').on('focus', function() {
        jQuery('.search-fields .search-destination button').removeClass('airbnb-active');
        jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery(this).addClass("airbnb-active").parent().addClass('z-index');
    });

});