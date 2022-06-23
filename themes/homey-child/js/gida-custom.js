jQuery(document).ready(function() {
    jQuery('.search-fields .search-destination button span.filter-option').addClass('text-bold ').css({"left": "8px", "top": "24px"});
    jQuery('#airbnb-anywhere').on('click', function() {
        jQuery('.airbnb-container').hide(10);
        jQuery('.search-fields').show(10).addClass('search-fields-animation'); //.animate({height: "60px", width: "800px"});
        jQuery('#homey-main-search').css({"height": "80px", "z-index": "999999"});
        jQuery('.header-nav').css({"border-bottom": "0"});
        jQuery('.airbnb-main-container').css({"top": "-10px"});
        jQuery('.search-fields .search-destination button').addClass('airbnb-active');
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

    // add date
    jQuery('#airbnb-anyweek').on('click', function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.airbnb-container').hide(10);
        jQuery('.search-fields').show(10).addClass('search-fields-animation'); //.animate({height: "60px", width: "800px"});
        jQuery('#homey-main-search').css({"height": "80px", "z-index": "999999"});
        jQuery('.header-nav').css({"border-bottom": "0"});
        jQuery('.airbnb-main-container').css({"top": "-10px"});

        jQuery(".search-fields .search-date-range-arrive input").focus();
    });

    // add guests
    jQuery('#airbnb-addguest').on('click', function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.airbnb-container').hide(10);
        jQuery('.search-fields').show(10).addClass('search-fields-animation'); //.animate({height: "60px", width: "800px"});
        jQuery('#homey-main-search').css({"height": "80px", "z-index": "999999"});
        jQuery('.header-nav').css({"border-bottom": "0"});
        jQuery('.airbnb-main-container').css({"top": "-10px"});

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
        jQuery('.search-fields').removeClass('search-fields-animation');
        jQuery('.search-fields').hide(10);
        jQuery('.airbnb-container').show(10);
        jQuery('#homey-main-search').css({"height": "0px", "z-index": "999"});
        jQuery('.airbnb-main-container').css({"top": "-76px"});
        jQuery('.header-nav').css({"border-bottom": "1px solid #d8dce1"})
    });

    // Get the container element
    var btnContainer = document.getElementById("tolerance-btn-container");

    // Get all buttons with class="btn" inside the container
    var btns = btnContainer.getElementsByClassName("btn-tolerance");

    // Loop through the buttons and add the active class to the current/clicked button
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function () {
            var current = document.getElementsByClassName("btn-tolerance-active");
            current[0].className = current[0].className.replace(" btn-tolerance-active", "");
            this.className += " btn-tolerance-active";
        });
    }

    // new Splide('.splide', {
    //     type: 'loop',
    //     height: '3rem',
    //     perPage: 3,
    //     // breakpoints: {
    //     //     640: {
    //     //         height: '6rem',
    //     //     },
    //     // },
    // }).mount();

    new Splide('.splide', {
        fixedWidth: 'auto',
        fixedHeight: '6rem',
        padding: { left: 50, right: 50},
        gap: '5rem',
        pagination: false,
        breakpoints: {
            640: {
                padding: { left: 20, right: 20},
                arrows: false,
            },
        },
    }).mount();

    if (jQuery('.splide__arrow.splide__arrow--prev').prop('disabled')) {
        jQuery('.splide__arrow.splide__arrow--prev').hide();
    } else {
        jQuery('.splide__arrow.splide__arrow--prev').show();
    }

    jQuery('.splide__arrow').click(function () {
        if (jQuery('.splide__arrow.splide__arrow--prev').prop('disabled')) {
            jQuery('.splide__arrow.splide__arrow--prev').hide();
        } else {
            jQuery('.splide__arrow.splide__arrow--prev').show();
        }

        if (jQuery('.splide__arrow.splide__arrow--next').prop('disabled')) {
            jQuery('.splide__arrow.splide__arrow--next').hide();
        } else {
            jQuery('.splide__arrow.splide__arrow--next').show();
        }
    });

    /* ------------------------------------------------------------------------ */
    /* Dropdown Search Menu
    /* ------------------------------------------------------------------------ */

    var custom_search_filter_open_btn = jQuery('.custom-search-banner-mobile .search-filters .custom-btn-btn');
    custom_search_filter_open_btn.on('click', function() {
        jQuery('.filter-like-airbnb .splide').toggle();
    });

    jQuery('.search-filter-wrap .search-filter-footer').append('<button type="button" class="btn btn-warning search-filter-close-btn">Close</button>')

    var search_filter_close_btn = jQuery('.search-filter-close-btn');
    search_filter_close_btn.on('click', function() {
        var check = jQuery(this).parents('.custom-search-banner-mobile').find('.search-filter');
        if( check.length > 0 ) {
            jQuery('.filter-like-airbnb .splide').show();
            jQuery(this).parents('.custom-search-banner-mobile').find('.search-filter').removeClass('search-filter-open');
            jQuery(this).parents('.custom-search-banner-mobile').find('.search-filters .custom-btn-btn').removeClass('active');
        } else {
            jQuery(this).parents('.custom-filter-form').find('.search-filter').removeClass('search-filter-open');
            jQuery(this).parents('.custom-filter-form').find('.search-filters .custom-btn-btn').removeClass('active');
        }
    });
});