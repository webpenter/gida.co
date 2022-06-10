jQuery(document).ready(function() {
    jQuery('#airbnb-anywhere, #airbnb-addguest').on('click', function() {
        jQuery('.airbnb-container').hide(500);
        jQuery('.search-fields').show(500).animate({height: "60px", width: "800px"});
        jQuery('#homey-main-search').css({"height": "80px"});
        jQuery('.header-nav').css({"border-bottom": "0"})
        jQuery('.airbnb-main-container').css({"top": "-10px"});
        jQuery('.search-fields .search-destination button').addClass('airbnb-active');
        jQuery('.search-fields .search-destination button span.filter-option').addClass('text-bold ').css({"left": "8px", "top": "24px"});
        jQuery('.search-fields .search-destination button').click();
    });

    jQuery('.search-fields .search-destination button').on('focus', function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.search-fields .search-guests input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery(this).addClass("airbnb-active");
    });

    jQuery('.search-fields .search-destination select[name=city]').change(function() {
        jQuery(".search-fields .main-search-date-range-js .search-date-range-arrive input").focus();
    });

    // add guests
    jQuery('#airbnb-addguest').on('click', function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.airbnb-container').hide(500);
        jQuery('.search-fields').show(500).animate({height: "60px", width: "800px"});
        jQuery('.search-fields .search-guests input').focus();
        jQuery('.search-fields .search-guests-wrap').css('display', 'block');
    });

    // jQuery('.search-fields .search-guests input').click(function() {
    //     jQuery(this).focus();
    //     jQuery('.search-fields .search-guests-wrap').css('display', 'block');
    // });
    //---------------------

    jQuery(".search-fields .main-search-date-range-js input").on('focus', function() {
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
        jQuery(this).click();
        jQuery('.search-fields .search-destination button').removeClass('airbnb-active');
        jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery(this).addClass("airbnb-active").parent().addClass('z-index');
    });

    jQuery(window).scroll(function() {
        jQuery('.search-fields').hide(500);
        jQuery('.airbnb-container').show(500);
        jQuery('#homey-main-search').css({"height": "0px"});
        jQuery('.airbnb-main-container').css({"top": "-76px"});
        jQuery('.header-nav').css({"border-bottom": "1px solid #d8dce1"})
    });

});