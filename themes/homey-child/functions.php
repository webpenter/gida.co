<?php
function homey_enqueue_styles() {

    // enqueue parent styles
    wp_enqueue_style('homey-parent-theme', get_template_directory_uri() .'/style.css');

    // enqueue child styles
    wp_enqueue_style('homey-child-theme', get_stylesheet_directory_uri() .'/style.css', array('homey-parent-theme'));
    wp_enqueue_style('gida-custom-css', get_stylesheet_directory_uri() .'/css/gida-custom.css', array('homey-parent-theme'));
    wp_enqueue_style('gida-splide-min-css', get_stylesheet_directory_uri() .'/css/splide.min.css');

    // enqueue child scripts
    wp_enqueue_script('gida-custom-js', get_stylesheet_directory_uri() .'/js/gida-custom.js', array('jquery'));
    wp_enqueue_script('gida-splide-min-js', get_stylesheet_directory_uri() .'/js/splide.min.js');
}
add_action('wp_enqueue_scripts', 'homey_enqueue_styles');


/********************* META BOX DEFINITIONS ***********************/

add_filter( 'rwmb_meta_boxes', 'custom_homey_register_metaboxes', 20, 0 );

if( !function_exists( 'custom_homey_register_metaboxes' ) ) {
    function custom_homey_register_metaboxes() {

        if (!class_exists('RW_Meta_Box')) {
            return;
        }

        global $meta_boxes, $wpdb;
        $prefix = 'homey_';
        // $meta_boxes = array();

        /* ===========================================================================================
        *   Taxonomies
        * ============================================================================================*/
        $meta_boxes[] = array(
            'id'        => 'custom_homey_taxonomies',
            'title'     => esc_html__('Filter Icon', 'homey' ),
            'taxonomies' => array( 'listing_type', 'listing_city', 'room_type', 'listing_country', 'listing_state', 'listing_area' ),
            

            'fields'    => array(
                array(
                    'name'      => esc_html__('Icon', 'homey' ),
                    'id'        => $prefix . 'taxonomy_icon',
                    'type'      => 'image_advanced',
                    'max_file_uploads' => 1,
                ),
            )
        );

        $meta_boxes = apply_filters('homey_theme_meta', $meta_boxes);

        return $meta_boxes;
    }
}
?>