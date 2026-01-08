<?php
/**
 * Admin functionality for Reviews
 *
 * @package Custom_Reviews_Display
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class CRD_Reviews_Admin {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_crd_review', array($this, 'save_review_meta'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        // Customize columns in admin list
        add_filter('manage_crd_review_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_crd_review_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'crd_review_details',
            __('Review Details', 'custom-reviews-display'),
            array($this, 'render_review_meta_box'),
            'crd_review',
            'normal',
            'high'
        );
    }

    /**
     * Render review meta box
     */
    public function render_review_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('crd_review_meta_box', 'crd_review_meta_box_nonce');

        // Get existing values
        $header = get_post_meta($post->ID, '_crd_review_header', true);
        $body = get_post_meta($post->ID, '_crd_review_body', true);
        $name = get_post_meta($post->ID, '_crd_review_name', true);
        $rating = get_post_meta($post->ID, '_crd_review_rating', true);
        $featured = get_post_meta($post->ID, '_crd_review_featured', true);

        ?>
        <div class="crd-admin-meta-box">
            <p class="description">
                <?php _e('Enter the review information below. The title field above can be used for internal reference.', 'custom-reviews-display'); ?>
            </p>

            <div class="crd-field-group">
                <label for="crd_review_header">
                    <strong><?php _e('Review Header/Title', 'custom-reviews-display'); ?></strong>
                    <span class="description"><?php _e('(This will be displayed as the review title)', 'custom-reviews-display'); ?></span>
                </label>
                <input type="text"
                       id="crd_review_header"
                       name="crd_review_header"
                       value="<?php echo esc_attr($header); ?>"
                       class="widefat"
                       placeholder="<?php esc_attr_e('e.g., Amazing Service!', 'custom-reviews-display'); ?>">
            </div>

            <div class="crd-field-group">
                <label for="crd_review_body">
                    <strong><?php _e('Review Body', 'custom-reviews-display'); ?></strong>
                    <span class="description"><?php _e('(The main review text)', 'custom-reviews-display'); ?></span>
                </label>
                <textarea id="crd_review_body"
                          name="crd_review_body"
                          rows="6"
                          class="widefat"
                          placeholder="<?php esc_attr_e('Enter the review text here...', 'custom-reviews-display'); ?>"><?php echo esc_textarea($body); ?></textarea>
            </div>

            <div class="crd-field-group">
                <label for="crd_review_name">
                    <strong><?php _e('Reviewer Name', 'custom-reviews-display'); ?></strong>
                    <span class="description"><?php _e('(Name of the person who gave the review)', 'custom-reviews-display'); ?></span>
                </label>
                <input type="text"
                       id="crd_review_name"
                       name="crd_review_name"
                       value="<?php echo esc_attr($name); ?>"
                       class="widefat"
                       placeholder="<?php esc_attr_e('e.g., John Smith', 'custom-reviews-display'); ?>">
            </div>

            <div class="crd-field-group crd-inline-fields">
                <div class="crd-inline-field">
                    <label for="crd_review_rating">
                        <strong><?php _e('Star Rating (Optional)', 'custom-reviews-display'); ?></strong>
                    </label>
                    <select id="crd_review_rating" name="crd_review_rating">
                        <option value=""><?php _e('No rating', 'custom-reviews-display'); ?></option>
                        <option value="5" <?php selected($rating, '5'); ?>>★★★★★ (5)</option>
                        <option value="4" <?php selected($rating, '4'); ?>>★★★★☆ (4)</option>
                        <option value="3" <?php selected($rating, '3'); ?>>★★★☆☆ (3)</option>
                        <option value="2" <?php selected($rating, '2'); ?>>★★☆☆☆ (2)</option>
                        <option value="1" <?php selected($rating, '1'); ?>>★☆☆☆☆ (1)</option>
                    </select>
                </div>

                <div class="crd-inline-field">
                    <label for="crd_review_featured">
                        <input type="checkbox"
                               id="crd_review_featured"
                               name="crd_review_featured"
                               value="1"
                               <?php checked($featured, '1'); ?>>
                        <?php _e('Featured Review', 'custom-reviews-display'); ?>
                        <span class="description"><?php _e('(Highlight this review)', 'custom-reviews-display'); ?></span>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Save review meta
     */
    public function save_review_meta($post_id, $post) {
        // Check nonce
        if (!isset($_POST['crd_review_meta_box_nonce']) ||
            !wp_verify_nonce($_POST['crd_review_meta_box_nonce'], 'crd_review_meta_box')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save header
        if (isset($_POST['crd_review_header'])) {
            update_post_meta($post_id, '_crd_review_header', sanitize_text_field($_POST['crd_review_header']));
        }

        // Save body
        if (isset($_POST['crd_review_body'])) {
            update_post_meta($post_id, '_crd_review_body', sanitize_textarea_field($_POST['crd_review_body']));
        }

        // Save name
        if (isset($_POST['crd_review_name'])) {
            update_post_meta($post_id, '_crd_review_name', sanitize_text_field($_POST['crd_review_name']));
        }

        // Save rating
        if (isset($_POST['crd_review_rating'])) {
            $rating = sanitize_text_field($_POST['crd_review_rating']);
            if (in_array($rating, array('1', '2', '3', '4', '5', ''))) {
                update_post_meta($post_id, '_crd_review_rating', $rating);
            }
        }

        // Save featured status
        if (isset($_POST['crd_review_featured'])) {
            update_post_meta($post_id, '_crd_review_featured', '1');
        } else {
            delete_post_meta($post_id, '_crd_review_featured');
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        global $post_type;

        if (('post.php' === $hook || 'post-new.php' === $hook) && 'crd_review' === $post_type) {
            wp_enqueue_style('crd-admin-css', CRD_PLUGIN_URL . 'admin/css/admin-style.css', array(), CRD_VERSION);
        }

        if ('crd_review_page_crd-settings' === $hook) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('crd-admin-js', CRD_PLUGIN_URL . 'admin/js/admin-script.js', array('jquery', 'wp-color-picker'), CRD_VERSION, true);
        }
    }

    /**
     * Set custom columns for admin list
     */
    public function set_custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = __('Internal Reference', 'custom-reviews-display');
        $new_columns['header'] = __('Review Header', 'custom-reviews-display');
        $new_columns['name'] = __('Reviewer', 'custom-reviews-display');
        $new_columns['rating'] = __('Rating', 'custom-reviews-display');
        $new_columns['featured'] = __('Featured', 'custom-reviews-display');
        $new_columns['date'] = $columns['date'];

        return $new_columns;
    }

    /**
     * Custom column content
     */
    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'header':
                $header = get_post_meta($post_id, '_crd_review_header', true);
                echo $header ? esc_html($header) : '—';
                break;

            case 'name':
                $name = get_post_meta($post_id, '_crd_review_name', true);
                echo $name ? esc_html($name) : '—';
                break;

            case 'rating':
                $rating = get_post_meta($post_id, '_crd_review_rating', true);
                if ($rating) {
                    echo str_repeat('★', intval($rating)) . str_repeat('☆', 5 - intval($rating));
                } else {
                    echo '—';
                }
                break;

            case 'featured':
                $featured = get_post_meta($post_id, '_crd_review_featured', true);
                echo $featured ? '⭐' : '—';
                break;
        }
    }
}
