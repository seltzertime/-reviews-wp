<?php
/**
 * Plugin Name: Custom Reviews Display
 * Plugin URI: https://github.com/seltzertime/-reviews-wp
 * Description: A customizable reviews display plugin with backend management and frontend shortcode display
 * Version: 1.0.8
 * Author: Cliff Cordes
 * Author URI: https://github.com/seltzertime
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-reviews-display
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CRD_VERSION', '1.0.8');
define('CRD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CRD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CRD_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once CRD_PLUGIN_DIR . 'includes/class-reviews-cpt.php';
require_once CRD_PLUGIN_DIR . 'includes/class-reviews-admin.php';
require_once CRD_PLUGIN_DIR . 'includes/class-reviews-settings.php';
require_once CRD_PLUGIN_DIR . 'includes/class-reviews-shortcode.php';

/**
 * Initialize the plugin
 */
function crd_init_plugin() {
    // Initialize custom post type
    new CRD_Reviews_CPT();

    // Initialize admin functionality
    if (is_admin()) {
        new CRD_Reviews_Admin();
        new CRD_Reviews_Settings();
    }

    // Initialize shortcode
    new CRD_Reviews_Shortcode();
}
add_action('plugins_loaded', 'crd_init_plugin');

/**
 * Activation hook
 */
function crd_activate_plugin() {
    // Register post type for flush_rewrite_rules
    $cpt = new CRD_Reviews_CPT();
    $cpt->register_post_type();

    // Flush rewrite rules
    flush_rewrite_rules();

    // Set default options
    if (!get_option('crd_settings')) {
        $defaults = array(
            'columns' => 3,
            'border_radius' => 10,
            'header_font_size' => 24,
            'body_font_size' => 16,
            'name_font_size' => 14,
            'rating_font_size' => 20,
            'header_font_family' => '',
            'body_font_family' => '',
            'name_font_family' => '',
            'card_padding' => 20,
            'card_color' => '#ffffff',
            'card_shadow' => true,
            'gap_between_cards' => 20,
            'text_color' => '#333333',
            'name_color' => '#666666',
            'rating_color' => '#ffa500',
            'mobile_columns' => 1,
            'profile_pic_size' => 50,
            'profile_pic_shape' => 'circle',
            'name_vertical_offset' => 0,
        );
        add_option('crd_settings', $defaults);
    }
}
register_activation_hook(__FILE__, 'crd_activate_plugin');

/**
 * Deactivation hook
 */
function crd_deactivate_plugin() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'crd_deactivate_plugin');

/**
 * Uninstall hook
 */
function crd_uninstall_plugin() {
    // Delete plugin options
    delete_option('crd_settings');

    // Delete all review posts
    $reviews = get_posts(array(
        'post_type' => 'crd_review',
        'posts_per_page' => -1,
        'post_status' => 'any',
    ));

    foreach ($reviews as $review) {
        wp_delete_post($review->ID, true);
    }
}
register_uninstall_hook(__FILE__, 'crd_uninstall_plugin');
