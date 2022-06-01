<?php
/*
Plugin Name: WP All Import - Homey Add-On
Plugin URI: http://www.wpallimport.com/
Description: Supporting imports into the Homey theme.
Version: 1.1.0
Author: Favethemes
*/


include "rapid-addon.php";

$homey_addon = new RapidAddon( 'Homey Add-On', 'homey_addon' );

$homey_addon->disable_default_images();

$homey_addon->import_images( 'homey_addon_gallery_images', 'Gallery Images' );

function homey_addon_gallery_images( $post_id, $attachment_id, $image_filepath, $import_options ) {
    add_post_meta( $post_id, 'homey_listing_images', $attachment_id ) || update_post_meta( $post_id, 'homey_listing_images', $attachment_id );
}

$prefix = 'homey_';

$homey_addon->add_field( $prefix.'listing_bedrooms', 'Number of Bedrooms', 'text', null, 'example: 2' );
$homey_addon->add_field( $prefix.'guests', 'Guests', 'text', null, 'example: 2' );
$homey_addon->add_field( $prefix.'beds', 'Beds', 'text', null, 'example: 3' );
$homey_addon->add_field( $prefix.'baths', 'Baths', 'text', null, 'example: 2' );
$homey_addon->add_field( $prefix.'listing_size', 'Listing Size', 'text', null, 'example: 700' );
$homey_addon->add_field( $prefix.'listing_size_unit', 'Size Unit', 'text', null, 'example: SqFt' );
$homey_addon->add_field( $prefix.'listing_rooms', 'Rooms', 'text', null, 'example: 2' );
$homey_addon->add_field( $prefix.'featured', 'Featured Listing?', 'radio', array(
    '0' => 'No',
    '1' => 'Yes',
) );
$homey_addon->add_field( $prefix.'instant_booking', 'Instant Booking', 'checkbox' );
$homey_addon->add_field( $prefix.'night_price', 'Nightly', 'text', null, 'example: 500' );
$homey_addon->add_field( $prefix.'weekends_price', 'Weekends Price', 'text', null, 'example: 700' );
//$homey_addon->add_field( $prefix.'weekends_days', 'Weekends Days', 'text', null, 'example: 700' );
$homey_addon->add_field( $prefix.'priceWeek', 'Weekly Price', 'text', null, 'example: 400' );
$homey_addon->add_field( $prefix.'priceMonthly', 'Monthly Price', 'text', null, 'example: 300' );
$homey_addon->add_field( $prefix.'allow_additional_guests', 'Allow Additional Guests?', 'radio', array(
    'no' => 'No',
    'yes' => 'Yes',
) );
$homey_addon->add_field( $prefix.'additional_guests_price', 'Additional Guests Price', 'text', null, 'example: 100' );
$homey_addon->add_field( $prefix.'cleaning_fee', 'Cleaning Fee', 'text', null, 'example: 50' );
$homey_addon->add_field( $prefix.'cleaning_fee_type', 'Cleaning Fee Type', 'radio', array(
    'daily' => 'daily',
    'per_stay' => 'Per Stay',
) );

$homey_addon->add_field( $prefix.'city_fee', 'City Fee', 'text', null, 'example: 50' );
$homey_addon->add_field( $prefix.'city_fee_type', 'City Fee Type', 'radio', array(
    'daily' => 'daily',
    'per_stay' => 'Per Stay',
) );
$homey_addon->add_field( $prefix.'security_deposit', 'Security Deposit', 'text', null, 'example: 150' );
$homey_addon->add_field( $prefix.'tax_rate', 'Tax Rate', 'text', null, 'example: 10(equal to 10%)' );
$homey_addon->add_field( $prefix.'cancellation_policy', 'Cancellation Policy', 'text', null, '' );
$homey_addon->add_field( $prefix.'min_book_days', 'Minimum Booking Days', 'text', null, 'example: 2' );
$homey_addon->add_field( $prefix.'max_book_days', 'Maximum Booking Days', 'text', null, 'example: 5' );
//$homey_addon->add_field( $prefix.'checkin_after', 'checkin_after', 'text', null, 'example: 5' );
//$homey_addon->add_field( $prefix.'checkout_before', 'checkout_before', 'text', null, 'example: 5' );
$homey_addon->add_field( $prefix.'smoke', 'Smoking Allowed?', 'radio', array(
    '0' => 'No',
    '1' => 'Yes',
) );
$homey_addon->add_field( $prefix.'pets', 'Pets Allowed?', 'radio', array(
    '0' => 'No',
    '1' => 'Yes',
) );
$homey_addon->add_field( $prefix.'party', 'Party Allowed?', 'radio', array(
    '0' => 'No',
    '1' => 'Yes',
) );
$homey_addon->add_field( $prefix.'children', 'Children Allowed?', 'radio', array(
    '0' => 'No',
    '1' => 'Yes',
) );
$homey_addon->add_field( $prefix.'additional_rules', 'additional_rules', 'textarea', null, '' );

$homey_addon->add_field( $prefix.'homeslider', 'Add this listing to Homepage Slider?', 'radio', array(
    'no'  => 'No',
    'yes' => 'Yes',
) );

$homey_addon->add_field( $prefix.'slider_image', 'Slider Image', 'image', null, 'Suggested size 1920 x 600. May use bigger or smaller image but keep the same height to width ratio and use the exact same size for all images in slider.' );

$homey_addon->add_field( $prefix.'video_url', 'Virtual Tour Video URL', 'text', null, 'Provide virtual tour video URL. YouTube, Vimeo, SWF File and MOV File are supported.' );



$homey_addon->add_field( $prefix.'show_map', 'Show Map', 'radio', array(
    '1' => 'Yes',
    '0' => 'No'
) );

$homey_addon->add_field(
    'location_settings',
    'Listing Map Location',
    'radio',
    array(
        'search_by_address'     => array(
            'Search by Address',
            $homey_addon->add_options(
                $homey_addon->add_field(
                    $prefix.'listing_address',
                    'Listing Address',
                    'text'
                ),
                'Google Geocode API Settings',
                array(
                    $homey_addon->add_field(
                        'address_geocode',
                        'Request Method',
                        'radio',
                        array(
                            'address_no_key'            => array(
                                'No API Key',
                                'Limited number of requests.'
                            ),
                            'address_google_developers' => array(
                                'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
                                $homey_addon->add_field(
                                    'address_google_developers_api_key',
                                    'API Key',
                                    'text'
                                ),
                                'Up to 2500 requests per day and 5 requests per second.'
                            ),
                            'address_google_for_work'   => array(
                                'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
                                $homey_addon->add_field(
                                    'address_google_for_work_client_id',
                                    'Google for Work Client ID',
                                    'text'
                                ),
                                $homey_addon->add_field(
                                    'address_google_for_work_digital_signature',
                                    'Google for Work Digital Signature',
                                    'text'
                                ),
                                'Up to 100,000 requests per day and 10 requests per second'
                            )
                        ) // end Request Method options array
                    ) // end Request Method nested radio field
                ) // end Google Geocode API Settings fields
            ) // end Google Gecode API Settings options panel
        ), // end Search by Address radio field
        'search_by_coordinates' => array(
            'Search by Coordinates',
            $homey_addon->add_field(
                'listing_latitude',
                'Latitude',
                'text',
                null,
                'Example: 34.0194543'
            ),
            $homey_addon->add_options(
                $homey_addon->add_field(
                    'listing_longitude',
                    'Longitude',
                    'text',
                    null,
                    'Example: -118.4911912'
                ),
                'Google Geocode API Settings',
                array(
                    $homey_addon->add_field(
                        'coord_geocode',
                        'Request Method',
                        'radio',
                        array(
                            'coord_no_key'            => array(
                                'No API Key',
                                'Limited number of requests.'
                            ),
                            'coord_google_developers' => array(
                                'Google Developers API Key - <a href="https://developers.google.com/maps/documentation/geocoding/#api_key">Get free API key</a>',
                                $homey_addon->add_field(
                                    'coord_google_developers_api_key',
                                    'API Key',
                                    'text'
                                ),
                                'Up to 2500 requests per day and 5 requests per second.'
                            ),
                            'coord_google_for_work'   => array(
                                'Google for Work Client ID & Digital Signature - <a href="https://developers.google.com/maps/documentation/business">Sign up for Google for Work</a>',
                                $homey_addon->add_field(
                                    'coord_google_for_work_client_id',
                                    'Google for Work Client ID',
                                    'text'
                                ),
                                $homey_addon->add_field(
                                    'coord_google_for_work_digital_signature',
                                    'Google for Work Digital Signature',
                                    'text'
                                ),
                                'Up to 100,000 requests per day and 10 requests per second'
                            )
                        ) // end Geocode API options array
                    ) // end Geocode nested radio field
                ) // end Geocode settings
            ) // end coordinates Option panel
        ) // end Search by Coordinates radio field
    ) // end Property Location radio field
);


// Services 
$homey_services_help = "If there are multiple services then separate each value with a '|'";
$homey_addon->add_options( false, 'Services Details', array(
    $homey_addon->add_field( 'service_name', 'Service Name', 'text', null, $homey_services_help ),
    $homey_addon->add_field( 'service_price', 'Service Price', 'text', null, $homey_services_help ),
    $homey_addon->add_field( 'service_des', 'Service Description', 'textarea', null, $homey_services_help ),
) );

// Bedrooms 
$homey_bedrooms_help = "If there are multiple bedrooms then separate each value with a '|'";
$homey_addon->add_options( false, 'Bedrooms Details', array(
    $homey_addon->add_field( 'acc_bedroom_name', 'Bedroom Name', 'text', null, $homey_bedrooms_help ),
    $homey_addon->add_field( 'acc_guests', 'Number of guests', 'text', null, $homey_bedrooms_help ),
    $homey_addon->add_field( 'acc_no_of_beds', 'Number of beds', 'text', null, $homey_bedrooms_help ),
    $homey_addon->add_field( 'acc_bedroom_type', 'Bed type', 'text', null, $homey_bedrooms_help ),
) );


$homey_addon->set_import_function( 'homey_addon_import' );

$homey_addon->admin_notice();
/* Check dependent plugins */
$homey_addon->admin_notice( 'Homey Add-on requires WP All Import <a href="http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=homey" target="_blank">Pro</a> or <a href="http://wordpress.org/plugins/wp-all-import" target="_blank">Free</a>, and the <a href="https://themeforest.net/item/homey-booking-wordpress-theme/23338013">Homey</a> theme.',
    array('themes' => array("Homey"))
);

$homey_addon->run( array(
    "themes"     => array("Homey"),
    "post_types" => array("listing")
) );

function homey_addon_import( $post_id, $data, $import_options, $article ) {

    global $homey_addon;
    $prefix = 'homey_';

    // all fields except for slider and image fields
    $fields = array(
        $prefix.'listing_bedrooms',
        $prefix.'guests',
        $prefix.'beds',
        $prefix.'baths',
        $prefix.'listing_size',
        $prefix.'listing_size_unit',
        $prefix.'listing_rooms',
        $prefix.'featured',
        $prefix.'instant_booking',
        $prefix.'night_price',
        $prefix.'weekends_price',
        $prefix.'priceWeek',
        $prefix.'priceMonthly',
        $prefix.'allow_additional_guests',
        $prefix.'additional_guests_price',
        $prefix.'cleaning_fee',
        $prefix.'cleaning_fee_type',
        $prefix.'city_fee',
        $prefix.'city_fee_type',
        $prefix.'security_deposit',
        $prefix.'tax_rate',
        $prefix.'cancellation_policy',
        $prefix.'min_book_days',
        $prefix.'max_book_days',
        $prefix.'smoke',
        $prefix.'pets',
        $prefix.'party',
        $prefix.'children',
        $prefix.'additional_rules',
        $prefix.'homeslider',
        $prefix.'video_url',
        $prefix.'show_map',
        $prefix.'listing_address',
    );

    // image fields
    $image_fields = array(
        $prefix.'slider_image',
    );

    // Services Fields
    $services_fields = array(
        'service_name',
        'service_price',
        'service_des',
    );

    // Bedrooms Fields
    $bedrooms_fields = array(
        'acc_bedroom_name',
        'acc_guests',
        'acc_no_of_beds',
        'acc_bedroom_type',
    );

    $fields = array_merge( $fields, $image_fields, $services_fields, $bedrooms_fields );

    // update everything in fields arrays
    foreach ( $fields as $field ) {

        if ( empty( $article['ID'] ) or $homey_addon->can_update_meta( $field, $import_options ) ) {

            // Image fields
            if ( in_array( $field, $image_fields ) ) {
                if ( $homey_addon->can_update_image( $import_options ) ) {

                    $id = $data[$field]['attachment_id'];

                    if ( strlen( $id ) == 0 ) {
                        delete_post_meta( $post_id, $field );
                    } else {
                        update_post_meta( $post_id, $field, $id );
                    }

                }
            } else if ( in_array( $field, $services_fields ) ) {
                foreach ( explode( "|", $data[$field] ) as $fp_key => $fp_value ) {
                    $t_fp_value = trim( $fp_value );
                    if (!empty($t_fp_value)) {
                        $services_meta[$fp_key][$field] = trim($fp_value);
                    }
                }
            } else if ( in_array( $field, $bedrooms_fields ) ) {
                foreach ( explode( "|", $data[$field] ) as $fp_key => $fp_value ) {
                    $t_fp_value = trim( $fp_value );
                    if (!empty($t_fp_value)) {
                        $bedrooms_meta[$fp_key][$field] = trim($fp_value);
                    }
                }
            } else {

                if ( strlen( $data[$field] ) == 0 ) {
                    delete_post_meta( $post_id, $field );
                } else {
                    update_post_meta( $post_id, $field, $data[$field] );
                }
            }
        }
    }

    update_post_meta( $post_id, 'homey_services', $services_meta );
    update_post_meta( $post_id, 'homey_accomodation', $bedrooms_meta );

    // clear image fields to override import settings
    $fields = array(
        'homey_listing_images'
    );

    if ( empty( $article['ID'] ) or $homey_addon->can_update_image( $import_options ) ) {

        foreach ( $fields as $field ) {

            delete_post_meta( $post_id, $field );

        }

    }


    // update property location
    $field = 'homey_listing_map_address';

    $address = $data[$field];

    $lat = $data['listing_latitude'];

    $long = $data['listing_longitude'];

    //  build search query
    if ( $data['location_settings'] == 'search_by_address' ) {

        $search = (!empty($address) ? 'address=' . rawurlencode( $address ) : null);

    } else {

        $search = (!empty($lat) && !empty($long) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null);

    }

    // build api key
    if ( $data['location_settings'] == 'search_by_address' ) {

        if ( $data['address_geocode'] == 'address_google_developers' && !empty($data['address_google_developers_api_key']) ) {

            $api_key = '&key=' . $data['address_google_developers_api_key'];

        } elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty($data['address_google_for_work_client_id']) && !empty($data['address_google_for_work_signature']) ) {

            $api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

        }

    } else {

        if ( $data['coord_geocode'] == 'coord_google_developers' && !empty($data['coord_google_developers_api_key']) ) {

            $api_key = '&key=' . $data['coord_google_developers_api_key'];

        } elseif ( $data['coord_geocode'] == 'coord_google_for_work' && !empty($data['coord_google_for_work_client_id']) && !empty($data['coord_google_for_work_signature']) ) {

            $api_key = '&client=' . $data['coord_google_for_work_client_id'] . '&signature=' . $data['coord_google_for_work_signature'];

        }

    }

    // if all fields are updateable and $search has a value
    if ( $homey_addon->can_update_meta( $field, $import_options ) && $homey_addon->can_update_meta( 'homey_listing_location', $import_options ) && !empty ($search) ) {

        // build $request_url for api call
        $request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;
        $curl = curl_init();

        curl_setopt( $curl, CURLOPT_URL, $request_url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

        $homey_addon->log( '- Getting location data from Geocoding API: ' . $request_url );

        $json = curl_exec( $curl );
        curl_close( $curl );

        // parse api response
        if ( !empty($json) ) {

            $details = json_decode( $json, true );

            if ( $data['location_settings'] == 'search_by_address' ) {

                $lat = $details[results][0][geometry][location][lat];

                $long = $details[results][0][geometry][location][lng];

            } else {

                $address = $details[results][0][formatted_address];

            }

        }

    }

    // update location fields
    $fields = array(
        'homey_listing_map_address'  => $address,
        'homey_listing_location' => $lat . ',' . $long
    );

    $homey_addon->log( '- Updating location data' );

    foreach ( $fields as $key => $value ) {

        if ( empty( $article['ID'] ) or $homey_addon->can_update_meta( $key, $import_options ) ) {

            update_post_meta( $post_id, $key, $value );

        }
    }
    
}