<?php
/**
 * Settings page for Reviews
 *
 * @package Custom_Reviews_Display
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class CRD_Reviews_Settings {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=crd_review',
            __('Display Settings', 'custom-reviews-display'),
            __('Display Settings', 'custom-reviews-display'),
            'manage_options',
            'crd-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'crd_settings_group',
            'crd_settings',
            array($this, 'sanitize_settings')
        );

        // Layout Settings Section
        add_settings_section(
            'crd_layout_section',
            __('Layout Settings', 'custom-reviews-display'),
            array($this, 'layout_section_callback'),
            'crd-settings'
        );

        add_settings_field(
            'columns',
            __('Number of Columns', 'custom-reviews-display'),
            array($this, 'columns_callback'),
            'crd-settings',
            'crd_layout_section'
        );

        add_settings_field(
            'gap_between_cards',
            __('Gap Between Cards (px)', 'custom-reviews-display'),
            array($this, 'gap_callback'),
            'crd-settings',
            'crd_layout_section'
        );

        add_settings_field(
            'mobile_columns',
            __('Mobile Columns', 'custom-reviews-display'),
            array($this, 'mobile_columns_callback'),
            'crd-settings',
            'crd_layout_section'
        );

        // Card Styling Section
        add_settings_section(
            'crd_card_section',
            __('Card Styling', 'custom-reviews-display'),
            array($this, 'card_section_callback'),
            'crd-settings'
        );

        add_settings_field(
            'border_radius',
            __('Border Radius (px)', 'custom-reviews-display'),
            array($this, 'border_radius_callback'),
            'crd-settings',
            'crd_card_section'
        );

        add_settings_field(
            'card_padding',
            __('Card Padding (px)', 'custom-reviews-display'),
            array($this, 'card_padding_callback'),
            'crd-settings',
            'crd_card_section'
        );

        add_settings_field(
            'card_color',
            __('Card Background Color', 'custom-reviews-display'),
            array($this, 'card_color_callback'),
            'crd-settings',
            'crd_card_section'
        );

        add_settings_field(
            'card_shadow',
            __('Card Shadow', 'custom-reviews-display'),
            array($this, 'card_shadow_callback'),
            'crd-settings',
            'crd_card_section'
        );

        add_settings_field(
            'card_border',
            __('Card Border', 'custom-reviews-display'),
            array($this, 'card_border_callback'),
            'crd-settings',
            'crd_card_section'
        );

        // Typography Section
        add_settings_section(
            'crd_typography_section',
            __('Typography', 'custom-reviews-display'),
            array($this, 'typography_section_callback'),
            'crd-settings'
        );

        add_settings_field(
            'header_font_size',
            __('Header Font Size (px)', 'custom-reviews-display'),
            array($this, 'header_font_size_callback'),
            'crd-settings',
            'crd_typography_section'
        );

        add_settings_field(
            'header_font_family',
            __('Header Font Family', 'custom-reviews-display'),
            array($this, 'header_font_family_callback'),
            'crd-settings',
            'crd_typography_section'
        );

        add_settings_field(
            'body_font_size',
            __('Body Font Size (px)', 'custom-reviews-display'),
            array($this, 'body_font_size_callback'),
            'crd-settings',
            'crd_typography_section'
        );

        add_settings_field(
            'body_font_family',
            __('Body Font Family', 'custom-reviews-display'),
            array($this, 'body_font_family_callback'),
            'crd-settings',
            'crd_typography_section'
        );

        add_settings_field(
            'name_font_size',
            __('Name Font Size (px)', 'custom-reviews-display'),
            array($this, 'name_font_size_callback'),
            'crd-settings',
            'crd_typography_section'
        );

        add_settings_field(
            'name_font_family',
            __('Name Font Family', 'custom-reviews-display'),
            array($this, 'name_font_family_callback'),
            'crd-settings',
            'crd_typography_section'
        );

        add_settings_field(
            'rating_font_size',
            __('Star Rating Size (px)', 'custom-reviews-display'),
            array($this, 'rating_font_size_callback'),
            'crd-settings',
            'crd_typography_section'
        );

        // Profile Picture Section
        add_settings_section(
            'crd_profile_section',
            __('Profile Picture Settings', 'custom-reviews-display'),
            array($this, 'profile_section_callback'),
            'crd-settings'
        );

        add_settings_field(
            'profile_pic_size',
            __('Profile Picture Size (px)', 'custom-reviews-display'),
            array($this, 'profile_pic_size_callback'),
            'crd-settings',
            'crd_profile_section'
        );

        add_settings_field(
            'profile_pic_shape',
            __('Profile Picture Shape', 'custom-reviews-display'),
            array($this, 'profile_pic_shape_callback'),
            'crd-settings',
            'crd_profile_section'
        );

        // Color Settings Section
        add_settings_section(
            'crd_color_section',
            __('Color Settings', 'custom-reviews-display'),
            array($this, 'color_section_callback'),
            'crd-settings'
        );

        add_settings_field(
            'text_color',
            __('Text Color', 'custom-reviews-display'),
            array($this, 'text_color_callback'),
            'crd-settings',
            'crd_color_section'
        );

        add_settings_field(
            'header_color',
            __('Header Color', 'custom-reviews-display'),
            array($this, 'header_color_callback'),
            'crd-settings',
            'crd_color_section'
        );

        add_settings_field(
            'name_color',
            __('Name Color', 'custom-reviews-display'),
            array($this, 'name_color_callback'),
            'crd-settings',
            'crd_color_section'
        );

        add_settings_field(
            'rating_color',
            __('Rating Star Color', 'custom-reviews-display'),
            array($this, 'rating_color_callback'),
            'crd-settings',
            'crd_color_section'
        );
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();

        // Numbers
        $number_fields = array('columns', 'border_radius', 'header_font_size', 'body_font_size', 'name_font_size', 'rating_font_size', 'card_padding', 'gap_between_cards', 'mobile_columns', 'profile_pic_size');
        foreach ($number_fields as $field) {
            if (isset($input[$field])) {
                $sanitized[$field] = absint($input[$field]);
            }
        }

        // Colors
        $color_fields = array('card_color', 'text_color', 'header_color', 'name_color', 'rating_color', 'card_border_color');
        foreach ($color_fields as $field) {
            if (isset($input[$field])) {
                $sanitized[$field] = sanitize_hex_color($input[$field]);
            }
        }

        // Fonts
        $font_fields = array('header_font_family', 'body_font_family', 'name_font_family');
        foreach ($font_fields as $field) {
            if (isset($input[$field])) {
                $sanitized[$field] = sanitize_text_field($input[$field]);
            }
        }

        // Checkboxes
        $sanitized['card_shadow'] = isset($input['card_shadow']) ? 1 : 0;
        $sanitized['card_border_enable'] = isset($input['card_border_enable']) ? 1 : 0;

        // Card border width
        if (isset($input['card_border_width'])) {
            $sanitized['card_border_width'] = absint($input['card_border_width']);
        }

        // Profile picture shape
        if (isset($input['profile_pic_shape']) && in_array($input['profile_pic_shape'], array('circle', 'square'))) {
            $sanitized['profile_pic_shape'] = $input['profile_pic_shape'];
        }

        return $sanitized;
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Check if settings were saved
        if (isset($_GET['settings-updated'])) {
            add_settings_error('crd_messages', 'crd_message', __('Settings Saved', 'custom-reviews-display'), 'updated');
        }

        settings_errors('crd_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <div class="crd-settings-wrapper">
                <div class="crd-settings-main">
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('crd_settings_group');
                        do_settings_sections('crd-settings');
                        submit_button(__('Save Settings', 'custom-reviews-display'));
                        ?>
                    </form>
                </div>

                <div class="crd-settings-sidebar">
                    <div class="crd-sidebar-box">
                        <h3><?php _e('How to Use', 'custom-reviews-display'); ?></h3>
                        <p><?php _e('Use the following shortcode to display your reviews:', 'custom-reviews-display'); ?></p>
                        <code>[custom_reviews]</code>

                        <h4><?php _e('Shortcode Options:', 'custom-reviews-display'); ?></h4>
                        <ul class="crd-shortcode-options">
                            <li><code>limit</code> - Number of reviews to show (e.g., limit="6")</li>
                            <li><code>columns</code> - Override default columns (e.g., columns="4")</li>
                            <li><code>featured_only</code> - Show only featured reviews (featured_only="true")</li>
                            <li><code>orderby</code> - Order by: date, rand, title (orderby="rand")</li>
                        </ul>

                        <h4><?php _e('Example:', 'custom-reviews-display'); ?></h4>
                        <code>[custom_reviews limit="6" featured_only="true"]</code>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    // Section Callbacks
    public function layout_section_callback() {
        echo '<p>' . __('Configure the layout of your review cards.', 'custom-reviews-display') . '</p>';
    }

    public function card_section_callback() {
        echo '<p>' . __('Customize the appearance of review cards.', 'custom-reviews-display') . '</p>';
    }

    public function typography_section_callback() {
        echo '<p>' . __('Set font sizes and families for review elements.', 'custom-reviews-display') . '</p>';
    }

    public function profile_section_callback() {
        echo '<p>' . __('Configure profile picture display for reviewers.', 'custom-reviews-display') . '</p>';
    }

    public function color_section_callback() {
        echo '<p>' . __('Choose colors for text and other elements.', 'custom-reviews-display') . '</p>';
    }

    // Field Callbacks
    public function columns_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['columns']) ? $options['columns'] : 3;
        ?>
        <input type="number" name="crd_settings[columns]" value="<?php echo esc_attr($value); ?>" min="1" max="6" class="small-text">
        <p class="description"><?php _e('Number of review cards per row (1-6)', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function gap_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['gap_between_cards']) ? $options['gap_between_cards'] : 20;
        ?>
        <input type="number" name="crd_settings[gap_between_cards]" value="<?php echo esc_attr($value); ?>" min="0" max="100" class="small-text">
        <p class="description"><?php _e('Space between review cards in pixels', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function mobile_columns_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['mobile_columns']) ? $options['mobile_columns'] : 1;
        ?>
        <input type="number" name="crd_settings[mobile_columns]" value="<?php echo esc_attr($value); ?>" min="1" max="3" class="small-text">
        <p class="description"><?php _e('Number of columns on mobile devices', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function border_radius_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['border_radius']) ? $options['border_radius'] : 10;
        ?>
        <input type="number" name="crd_settings[border_radius]" value="<?php echo esc_attr($value); ?>" min="0" max="100" class="small-text">
        <p class="description"><?php _e('Border radius in pixels', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function card_padding_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['card_padding']) ? $options['card_padding'] : 20;
        ?>
        <input type="number" name="crd_settings[card_padding]" value="<?php echo esc_attr($value); ?>" min="0" max="100" class="small-text">
        <p class="description"><?php _e('Inner padding of cards in pixels', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function card_color_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['card_color']) ? $options['card_color'] : '#ffffff';
        ?>
        <input type="text" name="crd_settings[card_color]" value="<?php echo esc_attr($value); ?>" class="crd-color-picker">
        <?php
    }

    public function card_shadow_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['card_shadow']) ? $options['card_shadow'] : 1;
        ?>
        <label>
            <input type="checkbox" name="crd_settings[card_shadow]" value="1" <?php checked($value, 1); ?>>
            <?php _e('Enable card shadow', 'custom-reviews-display'); ?>
        </label>
        <?php
    }

    public function card_border_callback() {
        $options = get_option('crd_settings');
        $enabled = isset($options['card_border_enable']) ? $options['card_border_enable'] : 0;
        $width = isset($options['card_border_width']) ? $options['card_border_width'] : 1;
        $color = isset($options['card_border_color']) ? $options['card_border_color'] : '#e0e0e0';
        ?>
        <label>
            <input type="checkbox" name="crd_settings[card_border_enable]" value="1" <?php checked($enabled, 1); ?>>
            <?php _e('Enable card border', 'custom-reviews-display'); ?>
        </label>
        <br><br>
        <label><?php _e('Border Width:', 'custom-reviews-display'); ?>
            <input type="number" name="crd_settings[card_border_width]" value="<?php echo esc_attr($width); ?>" min="1" max="10" class="small-text">px
        </label>
        <br><br>
        <label><?php _e('Border Color:', 'custom-reviews-display'); ?>
            <input type="text" name="crd_settings[card_border_color]" value="<?php echo esc_attr($color); ?>" class="crd-color-picker">
        </label>
        <?php
    }

    public function header_font_size_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['header_font_size']) ? $options['header_font_size'] : 24;
        ?>
        <input type="number" name="crd_settings[header_font_size]" value="<?php echo esc_attr($value); ?>" min="10" max="72" class="small-text">
        <?php
    }

    public function header_font_family_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['header_font_family']) ? $options['header_font_family'] : '';
        ?>
        <input type="text" name="crd_settings[header_font_family]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php _e('Enter custom font name (e.g., "Montserrat, sans-serif"). Leave empty for default.', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function body_font_size_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['body_font_size']) ? $options['body_font_size'] : 16;
        ?>
        <input type="number" name="crd_settings[body_font_size]" value="<?php echo esc_attr($value); ?>" min="10" max="48" class="small-text">
        <?php
    }

    public function body_font_family_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['body_font_family']) ? $options['body_font_family'] : '';
        ?>
        <input type="text" name="crd_settings[body_font_family]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php _e('Enter custom font name. Leave empty for default.', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function name_font_size_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['name_font_size']) ? $options['name_font_size'] : 14;
        ?>
        <input type="number" name="crd_settings[name_font_size]" value="<?php echo esc_attr($value); ?>" min="10" max="36" class="small-text">
        <?php
    }

    public function name_font_family_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['name_font_family']) ? $options['name_font_family'] : '';
        ?>
        <input type="text" name="crd_settings[name_font_family]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php _e('Enter custom font name. Leave empty for default.', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function text_color_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['text_color']) ? $options['text_color'] : '#333333';
        ?>
        <input type="text" name="crd_settings[text_color]" value="<?php echo esc_attr($value); ?>" class="crd-color-picker">
        <p class="description"><?php _e('Color for review body text', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function header_color_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['header_color']) ? $options['header_color'] : '#000000';
        ?>
        <input type="text" name="crd_settings[header_color]" value="<?php echo esc_attr($value); ?>" class="crd-color-picker">
        <p class="description"><?php _e('Color for review headers', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function name_color_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['name_color']) ? $options['name_color'] : '#666666';
        ?>
        <input type="text" name="crd_settings[name_color]" value="<?php echo esc_attr($value); ?>" class="crd-color-picker">
        <p class="description"><?php _e('Color for reviewer names', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function rating_color_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['rating_color']) ? $options['rating_color'] : '#ffa500';
        ?>
        <input type="text" name="crd_settings[rating_color]" value="<?php echo esc_attr($value); ?>" class="crd-color-picker">
        <p class="description"><?php _e('Color for star ratings', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function rating_font_size_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['rating_font_size']) ? $options['rating_font_size'] : 20;
        ?>
        <input type="number" name="crd_settings[rating_font_size]" value="<?php echo esc_attr($value); ?>" min="10" max="48" class="small-text">
        <p class="description"><?php _e('Size of star rating icons in pixels', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function profile_pic_size_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['profile_pic_size']) ? $options['profile_pic_size'] : 50;
        ?>
        <input type="number" name="crd_settings[profile_pic_size]" value="<?php echo esc_attr($value); ?>" min="20" max="200" class="small-text">
        <p class="description"><?php _e('Size of profile pictures in pixels (width and height)', 'custom-reviews-display'); ?></p>
        <?php
    }

    public function profile_pic_shape_callback() {
        $options = get_option('crd_settings');
        $value = isset($options['profile_pic_shape']) ? $options['profile_pic_shape'] : 'circle';
        ?>
        <select name="crd_settings[profile_pic_shape]">
            <option value="circle" <?php selected($value, 'circle'); ?>><?php _e('Circle', 'custom-reviews-display'); ?></option>
            <option value="square" <?php selected($value, 'square'); ?>><?php _e('Square', 'custom-reviews-display'); ?></option>
        </select>
        <p class="description"><?php _e('Shape of profile pictures', 'custom-reviews-display'); ?></p>
        <?php
    }
}
