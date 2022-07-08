jQuery(document).ready(function() {

    let open_close_filter = 0;

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
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-type button, .search-fields .search-filters button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.search-fields .search-type button').removeClass('airbnb-active');
        jQuery('.search-fields .search-guests input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-fields .search-filters button').removeClass('airbnb-active');

        if( open_close_filter == 1 ) {
            jQuery(document).find('#homey-main-search').find('.airbnb-main-container .search-filters button').click();
            open_close_filter = 0;
        }

        jQuery(this).addClass("airbnb-active");
    });

    jQuery('.search-fields .search-destination select[name=city]').change(function() {
        jQuery(".search-date-range-arrive input").focus();
    });

    // add date
    jQuery('#airbnb-anyweek').on('click', function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-type button, .search-fields .search-filters button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.airbnb-container').hide(10);
        jQuery('.search-fields').show(10).addClass('search-fields-animation'); //.animate({height: "60px", width: "800px"});
        jQuery('#homey-main-search').css({"height": "80px", "z-index": "999999"});
        jQuery('.header-nav').css({"border-bottom": "0"});
        jQuery('.airbnb-main-container').css({"top": "-10px"});

        jQuery(".search-date-range-arrive input").focus();
    });

    // add guests
    jQuery('#airbnb-addguest').on('click', function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-type button, .search-fields .search-filters button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.airbnb-container').hide(10);
        jQuery('.search-fields').show(10).addClass('search-fields-animation'); //.animate({height: "60px", width: "800px"});
        jQuery('#homey-main-search').css({"height": "80px", "z-index": "999999"});
        jQuery('.header-nav').css({"border-bottom": "0"});
        jQuery('.airbnb-main-container').css({"top": "-10px"});

        jQuery('.search-fields .search-guests input').focus();
        jQuery('.search-fields .search-guests-wrap').css('display', 'block');
    });

    // search filters
    jQuery('#airbnb-filter').on('click', function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-type button, .search-fields .search-filters button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.airbnb-container').hide(10);
        jQuery('.search-fields').show(10).addClass('search-fields-animation'); //.animate({height: "60px", width: "800px"});
        jQuery('#homey-main-search').css({"height": "80px", "z-index": "999999"});
        jQuery('.header-nav').css({"border-bottom": "0"});
        jQuery('.airbnb-main-container').css({"top": "-10px"});

    });
    
    jQuery(".search-filters button").click(function() {
        open_close_filter = 1;
    });

    // jQuery('.search-fields .search-guests input').click(function() {
    //     jQuery(this).focus();
    //     jQuery('.search-fields .search-guests-wrap').css('display', 'block');
    // });
    //---------------------

    jQuery(".search-fields .main-search-date-range-js input").on('focus', function() {
        jQuery('.search-fields .search-guests input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-fields .search-destination button').removeClass('airbnb-active');
        jQuery('.search-fields .search-type button').removeClass('airbnb-active');
        jQuery('.search-fields .search-filters button').removeClass('airbnb-active');

        focusedInput = jQuery(this).attr('name');
        if(focusedInput == "arrive"){
            jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
            jQuery(this).addClass("airbnb-active").parent().addClass('z-index');
        } else {
            jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
            jQuery(this).addClass("airbnb-active").parent().addClass('z-index');
        }

        if( open_close_filter == 1 ) {
            jQuery(document).find('#homey-main-search').find('.airbnb-main-container .search-filters button').click();
            open_close_filter = 0;
        }
    });

    jQuery('.search-fields .search-guests input').on('focus', function() {
        jQuery(this).click();
        jQuery('.search-fields .search-destination button').removeClass('airbnb-active');
        jQuery('.search-fields .search-type button').removeClass('airbnb-active');
        jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-fields .search-filters button').removeClass('airbnb-active');

        jQuery(this).addClass("airbnb-active").parent().addClass('z-index');

        if( open_close_filter == 1 ) {
            jQuery(document).find('#homey-main-search').find('.airbnb-main-container .search-filters button').click();
            open_close_filter = 0;
        }
    });

    jQuery('.search-fields .search-type button').on('focus', function() {
        jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-type button, .search-fields .search-filters button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.search-fields .search-destination button').removeClass('airbnb-active');
        jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-fields .search-guests input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-fields .search-filters button').removeClass('airbnb-active');

        jQuery(this).addClass("airbnb-active").parent().addClass('z-index');

        if( open_close_filter == 1 ) {
            jQuery(document).find('#homey-main-search').find('.airbnb-main-container .search-filters button').click();
            open_close_filter = 0;
        }
    });

    jQuery('.search-fields .search-filters button').on('focus', function() {
        // jQuery('.search-fields, .search-fields .search-destination button, .search-fields .search-type button, .search-fields .search-filters button, .search-fields .search-date-range input, .search-fields .search-guests input').css('background-color', 'rgb(246, 246, 246)');

        jQuery('.search-fields .search-destination button').removeClass('airbnb-active');
        jQuery('.search-fields .search-type button').removeClass('airbnb-active');
        jQuery('.search-date-range-arrive input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-date-range-depart input').removeClass('airbnb-active').parent().removeClass('z-index');
        jQuery('.search-fields .search-guests input').removeClass('airbnb-active').parent().removeClass('z-index');

        jQuery(this).addClass("airbnb-active").parent().addClass('z-index');
    });

    let check_homey_is_mobile = false;
    if (/Android|webOS|iPhone|iPad|iPod|tablet|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        check_homey_is_mobile = true;
    }

    if ( !check_homey_is_mobile ) {
        jQuery(window).scroll(function() {
            jQuery('.search-fields').removeClass('search-fields-animation');
            jQuery('.search-fields').hide(10);
            jQuery('.airbnb-container').show(10);
            jQuery('#homey-main-search').css({"height": "0px", "z-index": "999"});
            jQuery('.airbnb-main-container').css({"top": "-76px"});
            jQuery('.header-nav').css({"border-bottom": "1px solid #d8dce1"});

            if( open_close_filter == 1 ) {
                jQuery(document).find('#homey-main-search').find('.airbnb-main-container .search-filters button').click();
                open_close_filter = 0;
            }
        });
    }

    function homey_custom_sticky_nav_search_mobile() {
        var header_nav = jQuery('.header-nav');
        var header_area = jQuery('.nav-area');
        var header_area_height = header_area.innerHeight();
        jQuery(window).scroll(function() {
            var scroll = jQuery(window).scrollTop();
            var thisHeight = header_nav.outerHeight();
            var admin_nav = jQuery('#wpadminbar').height();

            if( admin_nav == 'null' ) { admin_nav = 0; }

            if (scroll >= header_area_height ) {
                header_area.addClass('sticky-nav-area');
                header_area.css('top', admin_nav);
                if (scroll >= header_area_height + 20 ) {
                    header_area.addClass('homey-in-view');
                    // if(is_top_header || !homey_is_transparent) {
                    //     section_body.css('padding-top',thisHeight);
                    // }
                }
            } else {
                header_area.removeClass('sticky-nav-area');
                header_area.removeAttr("style");
                if (scroll <= header_area_height + 20 ) {
                    header_area.removeClass('homey-in-view');
                }
                // if(is_top_header || !homey_is_transparent) {
                //     section_body.css('padding-top',0);
                // }
            }
        });
    }

    if( check_homey_is_mobile ) {
        homey_custom_sticky_nav_search_mobile();
    }

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
                // arrows: false,
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
        var check_mobile = jQuery(this).parents('.custom-search-banner-mobile').find('.search-filter');
        var check_custom = jQuery(this).parents('.custom-filter-form').find('.search-filter');

        if( check_mobile.length > 0 ) {
            jQuery('.filter-like-airbnb .splide').show();
            jQuery(this).parents('.custom-search-banner-mobile').find('.search-filter').removeClass('search-filter-open');
            jQuery(this).parents('.custom-search-banner-mobile').find('.search-filters .custom-btn-btn').removeClass('active');
        } else if ( check_custom.length > 0 ) {
            jQuery(this).parents('.custom-filter-form').find('.search-filter').removeClass('search-filter-open');
            jQuery(this).parents('.custom-filter-form').find('.search-filters .custom-btn-btn').removeClass('active');
        } else {
            jQuery(this).parents('#ban-airbnb-form').find('.search-filter').removeClass('search-filter-open');
            jQuery(this).parents('#ban-airbnb-form').find('.search-filters .custom-btn-btn').removeClass('active');
        }
    });

    /* ------------------------------------------------------------------------ */
    /* Airbnb like mobile responsive design
    /* ------------------------------------------------------------------------ */
    jQuery('.airbnb-btn-search').click(function() {
        jQuery('.airbnb-btn-mobile').show();
        jQuery(this).hide();

        jQuery('.airbnb-btn-mobile-search-type').hide();
        jQuery('.airbnb-btn-search-type').show();
    })

    jQuery('.airbnb-btn-search-type').click(function() {
        jQuery('.airbnb-btn-mobile-search-type').show();
        jQuery(this).hide();

        jQuery('.airbnb-btn-mobile').hide();
        jQuery('.airbnb-btn-search').show();
    })

    jQuery('.airbnb-btn-date-range').click(function() {
        jQuery('.airbnb-btn-search').show();
        jQuery('.airbnb-btn-mobile').hide();

        jQuery('.airbnb-btn-mobile-search-type').hide();
        jQuery('.airbnb-btn-search-type').show();

        jQuery('.search-date-range-arrive input').focus();
    })

    jQuery('.search-guests').click(function() {
        jQuery('.airbnb-btn-search').show();
        jQuery('.airbnb-btn-mobile').hide();

        jQuery('.airbnb-btn-search-type').show();
        jQuery('.airbnb-btn-mobile-search-type').hide();
    })

    // on change city
    jQuery('.airbnb-btn-mobile .search-destination .btn-group select.selectpicker').change(function() {
        jQuery('.airbnb-btn-search .city-name').html(
            jQuery('.search-destination .btn-group .btn span:not(:first)').text()
        )
    })

    // on change type
    jQuery('.airbnb-btn-mobile-search-type .search-type .btn-group select.selectpicker').change(function() {
        jQuery('.airbnb-btn-search-type .type-name').html(
            jQuery('.search-type .btn-group .btn span:not(:first)').text()
        )
    })
});