<?php
function homey_enqueue_styles() {

    // enqueue parent styles
    wp_enqueue_style('homey-parent-theme', get_template_directory_uri() .'/style.css');

    // enqueue child styles
    wp_enqueue_style('homey-child-theme', get_stylesheet_directory_uri() .'/style.css', array('homey-parent-theme'));
    wp_enqueue_style('gida-custom-css', get_stylesheet_directory_uri() .'/css/gida-custom.css', array('homey-parent-theme'));

    // enqueue child scripts
    wp_enqueue_script('gida-custom-js', get_stylesheet_directory_uri() .'/js/gida-custom.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'homey_enqueue_styles');

?>