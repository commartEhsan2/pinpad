<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function commart_pinpad_enqueue_admin_scripts($hook_suffix) {
    // Load color picker scripts and styles
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    // Load custom admin scripts and styles
    wp_enqueue_style('commart-pinpad-admin', plugins_url('/css/admin-styles.css', __FILE__));
    wp_enqueue_script('commart-pinpad-admin', plugins_url('/js/admin-scripts.js', __FILE__), array('jquery', 'wp-color-picker'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'commart_pinpad_enqueue_admin_scripts');

function commart_pinpad_enqueue_frontend_scripts() {
    // Load custom frontend styles
    wp_enqueue_style('commart-pinpad-frontend', plugins_url('/css/styles.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'commart_pinpad_enqueue_frontend_scripts');
