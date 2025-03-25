<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Commart_Pinpad_Messages {
    private $messages;

    public function __construct() {
        // Define default messages
        $this->messages = array(
            'enter_mobile' => 'شماره موبایل خود را وارد کنید.',
            'invalid_mobile' => 'شماره معتبر نیست.',
            'login_error' => 'شماره موجود نیست. ثبت نام کنید.',
            'registration_error' => 'شماره موجود است. لطفا وارد شوید.'
        );

        // Allow custom messages from settings
        $custom_messages = get_option('commart_pinpad_messages');
        if (is_array($custom_messages)) {
            $this->messages = array_merge($this->messages, $custom_messages);
        }
    }

    public function get_message($key) {
        return isset($this->messages[$key]) ? $this->messages[$key] : '';
    }

    public function get_all_messages() {
        return $this->messages;
    }
}

$GLOBALS['commart_pinpad_messages'] = new Commart_Pinpad_Messages();