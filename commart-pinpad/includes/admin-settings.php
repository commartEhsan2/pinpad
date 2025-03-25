<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Commart_Pinpad_Admin_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
    }

    public function add_admin_menu() {
        add_menu_page('Commart Pinpad', 'Commart Pinpad', 'manage_options', 'commart_pinpad', array($this, 'settings_page'));
    }

    public function settings_init() {
        register_setting('commartPinpad', 'commart_pinpad_settings');

        // Tab 1: Style
        add_settings_section(
            'commart_pinpad_style_section',
            __('تنظیمات سبک', 'commart-pinpad'),
            null,
            'commartPinpadStyle'
        );

        add_settings_field(
            'shortcode_info',
            __('شورتکد', 'commart-pinpad'),
            array($this, 'shortcode_info_render'),
            'commartPinpadStyle',
            'commart_pinpad_style_section'
        );

        add_settings_field(
            'button_color',
            __('تغییر رنگ دکمه‌ها', 'commart-pinpad'),
            array($this, 'button_color_render'),
            'commartPinpadStyle',
            'commart_pinpad_style_section'
        );

        add_settings_field(
            'pinpad_bg_color',
            __('تغییر رنگ بکگراند پین پد', 'commart-pinpad'),
            array($this, 'pinpad_bg_color_render'),
            'commartPinpadStyle',
            'commart_pinpad_style_section'
        );

        add_settings_field(
            'login_popup_bg_color',
            __('تغییر رنگ بکگراند پاپ آپ برای ورود', 'commart-pinpad'),
            array($this, 'login_popup_bg_color_render'),
            'commartPinpadStyle',
            'commart_pinpad_style_section'
        );

        add_settings_field(
            'register_popup_bg_color',
            __('تغییر رنگ بکگراند پاپ آپ برای ثبت نام', 'commart-pinpad'),
            array($this, 'register_popup_bg_color_render'),
            'commartPinpadStyle',
            'commart_pinpad_style_section'
        );

        // Tab 2: Advanced
        add_settings_section(
            'commart_pinpad_advanced_section',
            __('تنظیمات پیشرفته', 'commart-pinpad'),
            null,
            'commartPinpadAdvanced'
        );

        add_settings_field(
            'default_username',
            __('نام پیش فرض نام کاربری', 'commart-pinpad'),
            array($this, 'default_username_render'),
            'commartPinpadAdvanced',
            'commart_pinpad_advanced_section'
        );

        add_settings_field(
            'custom_css',
            __('استایل اختصاصی', 'commart-pinpad'),
            array($this, 'custom_css_render'),
            'commartPinpadAdvanced',
            'commart_pinpad_advanced_section'
        );

        add_settings_field(
            'show_errors',
            __('نمایش ارورها', 'commart-pinpad'),
            array($this, 'show_errors_render'),
            'commartPinpadAdvanced',
            'commart_pinpad_advanced_section'
        );

        add_settings_field(
            'script_priority',
            __('اولویت اسکریپت‌ها', 'commart-pinpad'),
            array($this, 'script_priority_render'),
            'commartPinpadAdvanced',
            'commart_pinpad_advanced_section'
        );
    }

    public function shortcode_info_render() {
        echo '<p>' . __('شورتکد: [commart_pinpad]', 'commart-pinpad') . '</p>';
    }

    public function button_color_render() {
        $options = get_option('commart_pinpad_settings');
        ?>
        <input type="text" name="commart_pinpad_settings[button_color]" value="<?php echo $options['button_color'] ?? ''; ?>" class="color-field">
        <?php
    }

    public function pinpad_bg_color_render() {
        $options = get_option('commart_pinpad_settings');
        ?>
        <input type="text" name="commart_pinpad_settings[pinpad_bg_color]" value="<?php echo $options['pinpad_bg_color'] ?? ''; ?>" class="color-field">
        <?php
    }

    public function login_popup_bg_color_render() {
        $options = get_option('commart_pinpad_settings');
        ?>
        <input type="text" name="commart_pinpad_settings[login_popup_bg_color]" value="<?php echo $options['login_popup_bg_color'] ?? ''; ?>" class="color-field">
        <?php
    }

    public function register_popup_bg_color_render() {
        $options = get_option('commart_pinpad_settings');
        ?>
        <input type="text" name="commart_pinpad_settings[register_popup_bg_color]" value="<?php echo $options['register_popup_bg_color'] ?? ''; ?>" class="color-field">
        <?php
    }

    public function default_username_render() {
        $options = get_option('commart_pinpad_settings');
        ?>
        <input type='text' name='commart_pinpad_settings[default_username]' value='<?php echo $options['default_username'] ?? 'commart'; ?>' maxlength='10'>
        <p class="description">این نام بخشی از پروتکل امنیتی است که میتوانید آن را مشخص کنید. فقط حروف انگلیسی بدون فاصله، حداکثر 10 کاراکتر.</p>
        <?php
    }

    public function custom_css_render() {
        $options = get_option('commart_pinpad_settings');
        ?>
        <textarea name='commart_pinpad_settings[custom_css]'><?php echo $options['custom_css'] ?? ''; ?></textarea>
        <?php
    }

    public function show_errors_render() {
        $options = get_option('commart_pinpad_settings');
        ?>
        <input type='checkbox' name='commart_pinpad_settings[show_errors]' <?php checked(isset($options['show_errors']) ? $options['show_errors'] : false, 1); ?> value='1'>
        <p class="description">نمایش ارورهای پلاگین.</p>
        <?php
    }

    public function script_priority_render() {
        $options = get_option('commart_pinpad_settings');
        ?>
        <input type='checkbox' name='commart_pinpad_settings[script_priority]' <?php checked(isset($options['script_priority']) ? $options['script_priority'] : false, 1); ?> value='1'>
        <p class="description">اولویت اسکریپت‌ها، جهت رفع تداخلات احتمالی.</p>
        <?php
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h2>Commart Pinpad</h2>
            <h2 class="nav-tab-wrapper">
                <a href="#tab-1" class="nav-tab nav-tab-active"><?php _e('سبک', 'commart-pinpad'); ?></a>
                <a href="#tab-2" class="nav-tab"><?php _e('پیشرفته', 'commart-pinpad'); ?></a>
            </h2>
            <form action='options.php' method='post'>
                <?php settings_fields('commartPinpad'); ?>
                <div id="tab-1" class="tab-content">
                    <?php do_settings_sections('commartPinpadStyle'); ?>
                </div>
                <div id="tab-2" class="tab-content" style="display: none;">
                    <?php do_settings_sections('commartPinpadAdvanced'); ?>
                </div>
                <?php submit_button(); ?>
            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.nav-tab').click(function(e) {
                    e.preventDefault();
                    $('.nav-tab').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active');
                    $('.tab-content').hide();
                    $($(this).attr('href')).show();
                });

                // Initialize the color picker
                $('.color-field').wpColorPicker();
            });
        </script>
        <style>
            .tab-content {
                display: none;
            }
            .tab-content:first-of-type {
                display: block;
            }
            .color-field {
                width: 100px;
                height: 36px;
                padding: 2px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
        </style>
        <?php
    }
}

new Commart_Pinpad_Admin_Settings();