<?php
/**
 * Plugin Name: Rano Floating WhatsApp Chat
 * Plugin URI: https://github.com/RanojitKumar/rano-floating-whatsapp-chat
 * Description: A modern, minimal floating WhatsApp chat button for WordPress websites. Easily add a WhatsApp chat button to engage with your visitors.
 * Version: 1.0.0
 * Author: Ranojit Kumar
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rano-floating-whatsapp-chat
 * Domain Path: /languages
 *
 * @package RanoFloatingWhatsAppChat
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('RFWC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RFWC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RFWC_PLUGIN_VERSION', '1.0.0');

/**
 * Main plugin class
 */
class RanoFloatingWhatsAppChat {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Load text domain for translations
        load_plugin_textdomain('rano-floating-whatsapp-chat', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Add hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'display_chat_button'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_shortcode('whatsapp_chat', array($this, 'shortcode'));
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        $default_options = array(
            'phone_number' => '',
            'message' => __('Hello! How can I help you?', 'rano-floating-whatsapp-chat'),
            'position' => 'bottom-right',
            'button_size' => '60',
            'button_color' => '#25D366',
            'show_on_mobile' => '1',
            'show_on_desktop' => '1',
            'animation' => 'pulse'
        );
        
        add_option('rfwc_settings', $default_options);
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clean up if needed
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style('rfwc-style', RFWC_PLUGIN_URL . 'assets/css/style.css', array(), RFWC_PLUGIN_VERSION);
        wp_enqueue_script('rfwc-script', RFWC_PLUGIN_URL . 'assets/js/script.js', array('jquery'), RFWC_PLUGIN_VERSION, true);
        
        // Pass settings to JavaScript
        $settings = get_option('rfwc_settings', array());
        wp_localize_script('rfwc-script', 'rfwc_ajax', array(
            'settings' => $settings
        ));
    }
    
    /**
     * Display chat button in footer
     */
    public function display_chat_button() {
        $settings = get_option('rfwc_settings', array());
        
        // Check if phone number is set
        if (empty($settings['phone_number'])) {
            return;
        }
        
        // Check device visibility settings
        $is_mobile = wp_is_mobile();
        if ($is_mobile && empty($settings['show_on_mobile'])) {
            return;
        }
        if (!$is_mobile && empty($settings['show_on_desktop'])) {
            return;
        }
        
        $this->render_chat_button($settings);
    }
    
    /**
     * Render chat button HTML
     */
    private function render_chat_button($settings) {
        $phone = esc_attr($settings['phone_number']);
        $message = esc_attr($settings['message']);
        $position = esc_attr($settings['position']);
        $size = esc_attr($settings['button_size']);
        $color = esc_attr($settings['button_color']);
        $animation = esc_attr($settings['animation']);
        
        $whatsapp_url = "https://wa.me/{$phone}?text=" . urlencode($message);
        
        echo '<div id="rfwc-chat-button" class="rfwc-position-' . $position . ' rfwc-animation-' . $animation . '" style="--rfwc-size: ' . $size . 'px; --rfwc-color: ' . $color . ';">';
        echo '<a href="' . esc_url($whatsapp_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Chat on WhatsApp', 'rano-floating-whatsapp-chat') . '">';
        echo '<svg viewBox="0 0 24 24" width="' . $size . '" height="' . $size . '">';
        echo '<path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.63z"/>';
        echo '</svg>';
        echo '</a>';
        echo '</div>';
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('WhatsApp Chat Settings', 'rano-floating-whatsapp-chat'),
            __('WhatsApp Chat', 'rano-floating-whatsapp-chat'),
            'manage_options',
            'rfwc-settings',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Initialize admin settings
     */
    public function admin_init() {
        register_setting('rfwc_settings_group', 'rfwc_settings');
        
        add_settings_section(
            'rfwc_general_section',
            __('General Settings', 'rano-floating-whatsapp-chat'),
            null,
            'rfwc-settings'
        );
        
        add_settings_field(
            'phone_number',
            __('WhatsApp Phone Number', 'rano-floating-whatsapp-chat'),
            array($this, 'phone_number_callback'),
            'rfwc-settings',
            'rfwc_general_section'
        );
        
        add_settings_field(
            'message',
            __('Default Message', 'rano-floating-whatsapp-chat'),
            array($this, 'message_callback'),
            'rfwc-settings',
            'rfwc_general_section'
        );
        
        add_settings_field(
            'position',
            __('Button Position', 'rano-floating-whatsapp-chat'),
            array($this, 'position_callback'),
            'rfwc-settings',
            'rfwc_general_section'
        );
        
        add_settings_field(
            'button_size',
            __('Button Size', 'rano-floating-whatsapp-chat'),
            array($this, 'button_size_callback'),
            'rfwc-settings',
            'rfwc_general_section'
        );
        
        add_settings_field(
            'button_color',
            __('Button Color', 'rano-floating-whatsapp-chat'),
            array($this, 'button_color_callback'),
            'rfwc-settings',
            'rfwc_general_section'
        );
        
        add_settings_field(
            'show_on_mobile',
            __('Show on Mobile', 'rano-floating-whatsapp-chat'),
            array($this, 'show_on_mobile_callback'),
            'rfwc-settings',
            'rfwc_general_section'
        );
        
        add_settings_field(
            'show_on_desktop',
            __('Show on Desktop', 'rano-floating-whatsapp-chat'),
            array($this, 'show_on_desktop_callback'),
            'rfwc-settings',
            'rfwc_general_section'
        );
        
        add_settings_field(
            'animation',
            __('Animation', 'rano-floating-whatsapp-chat'),
            array($this, 'animation_callback'),
            'rfwc-settings',
            'rfwc_general_section'
        );
    }
    
    /**
     * Admin page callback
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('WhatsApp Chat Settings', 'rano-floating-whatsapp-chat'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('rfwc_settings_group');
                do_settings_sections('rfwc-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Phone number field callback
     */
    public function phone_number_callback() {
        $settings = get_option('rfwc_settings', array());
        $value = isset($settings['phone_number']) ? $settings['phone_number'] : '';
        echo '<input type="text" name="rfwc_settings[phone_number]" value="' . esc_attr($value) . '" placeholder="1234567890" />';
        echo '<p class="description">' . __('Enter phone number with country code (without + sign)', 'rano-floating-whatsapp-chat') . '</p>';
    }
    
    /**
     * Message field callback
     */
    public function message_callback() {
        $settings = get_option('rfwc_settings', array());
        $value = isset($settings['message']) ? $settings['message'] : __('Hello! How can I help you?', 'rano-floating-whatsapp-chat');
        echo '<textarea name="rfwc_settings[message]" rows="3" cols="50">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">' . __('Default message that will be pre-filled in WhatsApp chat', 'rano-floating-whatsapp-chat') . '</p>';
    }
    
    /**
     * Position field callback
     */
    public function position_callback() {
        $settings = get_option('rfwc_settings', array());
        $value = isset($settings['position']) ? $settings['position'] : 'bottom-right';
        $positions = array(
            'bottom-right' => __('Bottom Right', 'rano-floating-whatsapp-chat'),
            'bottom-left' => __('Bottom Left', 'rano-floating-whatsapp-chat'),
            'top-right' => __('Top Right', 'rano-floating-whatsapp-chat'),
            'top-left' => __('Top Left', 'rano-floating-whatsapp-chat')
        );
        
        echo '<select name="rfwc_settings[position]">';
        foreach ($positions as $key => $label) {
            echo '<option value="' . esc_attr($key) . '"' . selected($value, $key, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    /**
     * Button size field callback
     */
    public function button_size_callback() {
        $settings = get_option('rfwc_settings', array());
        $value = isset($settings['button_size']) ? $settings['button_size'] : '60';
        echo '<input type="number" name="rfwc_settings[button_size]" value="' . esc_attr($value) . '" min="40" max="100" />';
        echo '<p class="description">' . __('Button size in pixels (40-100)', 'rano-floating-whatsapp-chat') . '</p>';
    }
    
    /**
     * Button color field callback
     */
    public function button_color_callback() {
        $settings = get_option('rfwc_settings', array());
        $value = isset($settings['button_color']) ? $settings['button_color'] : '#25D366';
        echo '<input type="color" name="rfwc_settings[button_color]" value="' . esc_attr($value) . '" />';
    }
    
    /**
     * Show on mobile field callback
     */
    public function show_on_mobile_callback() {
        $settings = get_option('rfwc_settings', array());
        $value = isset($settings['show_on_mobile']) ? $settings['show_on_mobile'] : '1';
        echo '<input type="checkbox" name="rfwc_settings[show_on_mobile]" value="1"' . checked($value, '1', false) . ' />';
        echo '<label>' . __('Show chat button on mobile devices', 'rano-floating-whatsapp-chat') . '</label>';
    }
    
    /**
     * Show on desktop field callback
     */
    public function show_on_desktop_callback() {
        $settings = get_option('rfwc_settings', array());
        $value = isset($settings['show_on_desktop']) ? $settings['show_on_desktop'] : '1';
        echo '<input type="checkbox" name="rfwc_settings[show_on_desktop]" value="1"' . checked($value, '1', false) . ' />';
        echo '<label>' . __('Show chat button on desktop devices', 'rano-floating-whatsapp-chat') . '</label>';
    }
    
    /**
     * Animation field callback
     */
    public function animation_callback() {
        $settings = get_option('rfwc_settings', array());
        $value = isset($settings['animation']) ? $settings['animation'] : 'pulse';
        $animations = array(
            'none' => __('None', 'rano-floating-whatsapp-chat'),
            'pulse' => __('Pulse', 'rano-floating-whatsapp-chat'),
            'bounce' => __('Bounce', 'rano-floating-whatsapp-chat'),
            'shake' => __('Shake', 'rano-floating-whatsapp-chat')
        );
        
        echo '<select name="rfwc_settings[animation]">';
        foreach ($animations as $key => $label) {
            echo '<option value="' . esc_attr($key) . '"' . selected($value, $key, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    /**
     * Shortcode callback
     */
    public function shortcode($atts) {
        $atts = shortcode_atts(array(
            'phone' => '',
            'message' => '',
            'text' => __('Chat on WhatsApp', 'rano-floating-whatsapp-chat'),
            'target' => '_blank'
        ), $atts);
        
        $settings = get_option('rfwc_settings', array());
        $phone = !empty($atts['phone']) ? $atts['phone'] : $settings['phone_number'];
        $message = !empty($atts['message']) ? $atts['message'] : $settings['message'];
        
        if (empty($phone)) {
            return '';
        }
        
        $whatsapp_url = "https://wa.me/{$phone}?text=" . urlencode($message);
        
        return '<a href="' . esc_url($whatsapp_url) . '" target="' . esc_attr($atts['target']) . '" rel="noopener noreferrer" class="rfwc-shortcode-link">' . esc_html($atts['text']) . '</a>';
    }
}

// Initialize the plugin
new RanoFloatingWhatsAppChat();