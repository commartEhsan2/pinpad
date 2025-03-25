<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function commart_pinpad_ajax_login() {
    check_ajax_referer('commart_pinpad_nonce', 'nonce');

    $username = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);

    $user = wp_authenticate($username, $password);
    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => $GLOBALS['commart_pinpad_messages']->get_message('login_error')));
    } else {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        wp_send_json_success();
    }
}
add_action('wp_ajax_nopriv_commart_pinpad_login', 'commart_pinpad_ajax_login');

function commart_pinpad_ajax_register() {
    check_ajax_referer('commart_pinpad_nonce', 'nonce');

    $username = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);

    if (username_exists($username) || email_exists($username . '@commart.ir')) {
        wp_send_json_error(array('message' => $GLOBALS['commart_pinpad_messages']->get_message('registration_error')));
    } else {
        $user_id = wp_create_user($username, $password, $username . '@commart.ir');
        wp_send_json_success(array('user_id' => $user_id));
    }
}
add_action('wp_ajax_nopriv_commart_pinpad_register', 'commart_pinpad_ajax_register');