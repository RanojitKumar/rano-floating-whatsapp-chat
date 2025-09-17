<?php
/**
 * Uninstall script for Rano Floating WhatsApp Chat
 * 
 * This file is executed when the plugin is uninstalled via WordPress admin.
 * It cleans up all plugin data from the database.
 *
 * @package RanoFloatingWhatsAppChat
 */

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('rfwc_settings');

// For multisite installations
if (is_multisite()) {
    $sites = get_sites();
    foreach ($sites as $site) {
        switch_to_blog($site->blog_id);
        delete_option('rfwc_settings');
        restore_current_blog();
    }
}

// Clear any cached data that has been removed
wp_cache_flush();