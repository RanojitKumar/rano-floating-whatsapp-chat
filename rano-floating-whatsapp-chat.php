<?php
/*
Plugin Name: Rano Floating WhatsApp Chat
Description: Adds a modern floating WhatsApp chat button to your website with selectable country codes, configurable positioning and a custom popup message.
Version: 1.1
Author: Ranojit K.
Author URI: https://ranojit.com/
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue front‑end styles
function fwac_enqueue_scripts() {
    // Register the main stylesheet used on the front‑end.  
    wp_enqueue_style(
        'fwac-style',
        plugin_dir_url(__FILE__) . 'style.css',
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'style.css')
    );
}
add_action('wp_enqueue_scripts', 'fwac_enqueue_scripts');

/**
 * Enqueue scripts and styles for the settings page in the WordPress admin.
 *
 * We rely on the popular `intl-tel-input` library hosted on a CDN to provide
 * a country selector with dial codes.  This prevents the need to manually
 * maintain a long list of country codes and keeps the plugin lightweight.
 */
function fwac_admin_enqueue_scripts($hook_suffix) {
    // Only enqueue on our plugin settings page to avoid unnecessarily
    // loading assets across the entire admin.
    if ($hook_suffix !== 'toplevel_page_fwac-whatsapp-chat') {
        return;
    }
    // International Telephone Input library styles and scripts
    wp_enqueue_style(
        'intl-tel-input-css',
        'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css',
        array(),
        '17.0.8'
    );
    wp_enqueue_script(
        'intl-tel-input',
        'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js',
        array('jquery'),
        '17.0.8',
        true
    );
    // Utils script is required for proper formatting and number retrieval
    wp_enqueue_script(
        'intl-tel-utils',
        'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js',
        array('intl-tel-input'),
        '17.0.8',
        true
    );
    // Our admin script to initialise the phone input and ensure the
    // saved value includes the dial code.  Version uses filemtime to bust caches
    $admin_js_path = plugin_dir_path(__FILE__) . 'admin.js';
    wp_enqueue_script(
        'fwac-admin',
        plugin_dir_url(__FILE__) . 'admin.js',
        array('jquery', 'intl-tel-input'),
        file_exists($admin_js_path) ? filemtime($admin_js_path) : time(),
        true
    );
}
add_action('admin_enqueue_scripts', 'fwac_admin_enqueue_scripts');

// Add WhatsApp chat button and popup to the website footer
function fwac_add_whatsapp_button() {
    // Retrieve plugin options and fall back to reasonable defaults
    $phone_number = get_option('fwac_phone_number', '8801xxxxxxxxxx');
    $message_text = get_option('fwac_message_text', 'I%27m%20interested');
    $position      = get_option('fwac_position', 'bottom-right');
    $popup_text    = get_option('fwac_popup_text', __('Chat with us on WhatsApp!', 'fwac'));

    // Ensure the position value is one of our allowed options
    $allowed_positions = array('bottom-right', 'bottom-left', 'top-right', 'top-left');
    if (!in_array($position, $allowed_positions, true)) {
        $position = 'bottom-right';
    }

    // Compose CSS class for positioning
    $position_class = 'fwac-position-' . sanitize_html_class($position);

    // Build the WhatsApp URL; remove leading plus if present as wa.me expects numbers only
    $formatted_number = ltrim($phone_number, '+');
    $whatsapp_url = 'https://wa.me/' . rawurlencode($formatted_number) . '?text=' . rawurlencode($message_text);

    // Output the container, button and popup. Use esc_url_raw, esc_html, etc. to ensure safe output.
    echo '<div class="fwac-whatsapp-container ' . esc_attr($position_class) . '">';
    echo '<a aria-label="' . esc_attr__('Chat on WhatsApp', 'fwac') . '" href="' . esc_url_raw($whatsapp_url) . '" class="fwac-whatsapp-button" target="_blank" rel="noopener noreferrer">';
    echo '<img alt="' . esc_attr__('Chat on WhatsApp', 'fwac') . '" src="' . esc_url(plugin_dir_url(__FILE__) . 'whatsapp.svg') . '" />';
    echo '</a>';
    // Popup container. Use esc_html for text to avoid HTML injection
    echo '<div class="fwac-popup-text">' . esc_html($popup_text) . '</div>';
    echo '</div>';
}
add_action('wp_footer', 'fwac_add_whatsapp_button');

// Create settings menu and page
function fwac_add_admin_menu() {
    add_menu_page(
        'Rano WhatsApp Chat',  // Page title
        'Rano WhatsApp Chat',  // Menu title
        'manage_options',      // Capability required
        'fwac-whatsapp-chat',  // Menu slug
        'fwac_settings_page',  // Function to display the page
        'dashicons-whatsapp',  // Icon
        80                     // Position
    );
}
add_action('admin_menu', 'fwac_add_admin_menu');

// Create the settings page
function fwac_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Rano WhatsApp Chat Settings', 'fwac'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('fwac_options_group');
            do_settings_sections('fwac-whatsapp-chat');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('WhatsApp Phone Number', 'fwac'); ?></th>
                    <td>
                        <input type="tel" name="fwac_phone_number" id="fwac_phone_number" value="<?php echo esc_attr(get_option('fwac_phone_number', '8801xxxxxxxxxx')); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e('Enter the WhatsApp phone number. Use the drop‑down to select your country – the dial code will be added automatically.', 'fwac'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Default Message Text', 'fwac'); ?></th>
                    <td>
                        <input type="text" name="fwac_message_text" value="<?php echo esc_attr(get_option('fwac_message_text', 'I%27m%20interested')); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e('Enter the default message text when users click the chat button.', 'fwac'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Button Position', 'fwac'); ?></th>
                    <td>
                        <?php $saved_position = get_option('fwac_position', 'bottom-right'); ?>
                        <select name="fwac_position">
                            <option value="bottom-right" <?php selected($saved_position, 'bottom-right'); ?>><?php esc_html_e('Bottom Right', 'fwac'); ?></option>
                            <option value="bottom-left"  <?php selected($saved_position, 'bottom-left');  ?>><?php esc_html_e('Bottom Left', 'fwac'); ?></option>
                            <option value="top-right"    <?php selected($saved_position, 'top-right');   ?>><?php esc_html_e('Top Right', 'fwac'); ?></option>
                            <option value="top-left"     <?php selected($saved_position, 'top-left');    ?>><?php esc_html_e('Top Left', 'fwac'); ?></option>
                        </select>
                        <p class="description"><?php esc_html_e('Choose where the chat button appears on your site.', 'fwac'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Popup Text', 'fwac'); ?></th>
                    <td>
                        <input type="text" name="fwac_popup_text" value="<?php echo esc_attr(get_option('fwac_popup_text', __('Chat with us on WhatsApp!', 'fwac'))); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e('Text displayed in the small popup above the chat button.', 'fwac'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
function fwac_register_settings() {
    register_setting('fwac_options_group', 'fwac_phone_number');
    register_setting('fwac_options_group', 'fwac_message_text');
    register_setting('fwac_options_group', 'fwac_position');
    register_setting('fwac_options_group', 'fwac_popup_text');
}
add_action('admin_init', 'fwac_register_settings');

