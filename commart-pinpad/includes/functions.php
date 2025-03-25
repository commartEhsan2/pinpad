<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function commart_pinpad_enqueue_scripts() {
    wp_enqueue_style('commart-pinpad', plugins_url('/css/styles.css', __FILE__));
    wp_enqueue_script('commart-pinpad', plugins_url('/js/scripts.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('commart-pinpad', 'commartPinpad', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'messages' => $GLOBALS['commart_pinpad_messages']->get_all_messages(),
    ));
}
add_action('wp_enqueue_scripts', 'commart_pinpad_enqueue_scripts');

function commart_pinpad_render() {
    ob_start();
    ?>
    <div id="commart-pinpad">
        <div class="pinpad-header">
            <span id="pinpad-title">ورود</span>
        </div>
        <div class="pinpad-body">
            <div id="number-display">&nbsp;</div>
            <div class="keypad">
                <button class="num">1</button>
                <button class="num">2</button>
                <button class="num">3</button>
                <button class="num">4</button>
                <button class="num">5</button>
                <button class="num">6</button>
                <button class="num">7</button>
                <button class="num">8</button>
                <button class="num">9</button>
                <button id="swap-button">
                    <!-- Swap button icon for login -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 8C8.44772 8 8 8.44772 8 9C8 9.55229 8.44772 10 9 10H11V12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12V10H15C15.5523 10 16 9.55229 16 9C16 8.44772 15.5523 8 15 8H13V6C13 5.44772 12.5523 5 12 5C11.4477 5 11 5.44772 11 6V8H9Z" fill="currentColor"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4 4C4 2.34315 5.34315 1 7 1H17C18.6569 1 20 2.34315 20 4V14C20 15.6569 18.6569 17 17 17H7C5.34315 17 4 15.6569 4 14V4ZM7 3H17C17.5523 3 18 3.44772 18 4V14C18 14.5523 17.5523 15 17 15H7C6.44772 15 6 14.5523 6 14V4C6 3.44772 6.44772 3 7 3Z" fill="currentColor"/>
                        <path d="M5 20C4.44772 20 4 20.4477 4 21C4 21.5523 4.44772 22 5 22H19C19.5523 22 20 21.5523 20 21C20 20.4477 19.5523 20 19 20H5Z" fill="currentColor"/>
                    </svg>
                </button>
                <button class="num">0</button>
                <button id="backspace-button">
                    <!-- Backspace button icon -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.3284 11.0001V13.0001L7.50011 13.0001L10.7426 16.2426L9.32842 17.6568L3.67157 12L9.32842 6.34314L10.7426 7.75735L7.49988 11.0001L20.3284 11.0001Z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
        </div>
        <div id="login-form" class="hidden">
            <?php echo do_shortcode('[RM_Login]'); ?>
        </div>
        <div id="register-form" class="hidden">
            <?php echo do_shortcode('[RM_Forms id="2"]'); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('commart_pinpad', 'commart_pinpad_render');