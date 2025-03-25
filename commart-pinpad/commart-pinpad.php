<?php
/*
Plugin Name: Commart Pinpad
Description: A plugin for one-step login and registration with a Pinpad.
Version: 1.0
Author: Ehsan Pihadi
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Commart_Pinpad {
    public function __construct() {
        require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';
        require_once plugin_dir_path(__FILE__) . 'includes/ajax-handlers.php';
        require_once plugin_dir_path(__FILE__) . 'includes/messages.php';
        require_once plugin_dir_path(__FILE__) . 'includes/security.php';
        require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
    }
}

new Commart_Pinpad();