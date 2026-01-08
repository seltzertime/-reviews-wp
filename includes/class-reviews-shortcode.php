<?php
/**
 * Shortcode handler for Reviews
 *
 * @package Custom_Reviews_Display
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class CRD_Reviews_Shortcode {

    /**
     * Constructor
     */
    public function __construct() {
        add_shortcode('custom_reviews', array($this, 'render_reviews'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
    }

    /**
     * Render reviews shortcode
     */
    public function render_reviews($atts) {
        // Parse shortcode attributes
        $atts = shortcode_atts(array(
            'limit'         => -1,
            'columns'       => '',
            'featured_only' => 'false',
            'orderby'       => 'date',
            'order'         => 'DESC',
        ), $atts, 'custom_reviews');

        // Get settings
        $settings = get_option('crd_settings');

        // Build query args
        $args = array(
            'post_type'      => 'crd_review',
            'posts_per_page' => intval($atts['limit']),
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
            'post_status'    => 'publish',
        );

        // Filter by featured if requested
        if ('true' === strtolower($atts['featured_only'])) {
            $args['meta_query'] = array(
                array(
                    'key'   => '_crd_review_featured',
                    'value' => '1',
                ),
            );
        }

        // Query reviews
        $reviews = new WP_Query($args);

        if (!$reviews->have_posts()) {
            return '<p class="crd-no-reviews">' . __('No reviews found.', 'custom-reviews-display') . '</p>';
        }

        // Determine columns
        $columns = !empty($atts['columns']) ? intval($atts['columns']) : (isset($settings['columns']) ? $settings['columns'] : 3);

        // Start output buffer
        ob_start();

        // Generate unique ID for this shortcode instance
        $instance_id = 'crd-' . wp_rand();

        ?>
        <div class="crd-reviews-container" id="<?php echo esc_attr($instance_id); ?>">
            <div class="crd-reviews-grid">
                <?php
                while ($reviews->have_posts()) {
                    $reviews->the_post();
                    $this->render_single_review(get_the_ID(), $settings);
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>

        <?php
        // Generate inline styles for this instance
        $this->generate_inline_styles($instance_id, $settings, $columns);

        return ob_get_clean();
    }

    /**
     * Render a single review card
     */
    private function render_single_review($post_id, $settings) {
        $header = get_post_meta($post_id, '_crd_review_header', true);
        $body = get_post_meta($post_id, '_crd_review_body', true);
        $name = get_post_meta($post_id, '_crd_review_name', true);
        $rating = get_post_meta($post_id, '_crd_review_rating', true);
        $featured = get_post_meta($post_id, '_crd_review_featured', true);
        $profile_pic_id = get_post_meta($post_id, '_crd_review_profile_pic', true);

        $profile_pic_shape = isset($settings['profile_pic_shape']) ? $settings['profile_pic_shape'] : 'circle';
        $profile_pic_class = $profile_pic_shape === 'square' ? 'crd-review-profile-pic crd-square' : 'crd-review-profile-pic';

        $card_class = 'crd-review-card';
        if ($featured) {
            $card_class .= ' crd-featured';
        }
        ?>
        <div class="<?php echo esc_attr($card_class); ?>">
            <?php if ($rating) : ?>
                <div class="crd-review-rating">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= intval($rating)) {
                            echo '<span class="crd-star crd-star-filled">★</span>';
                        } else {
                            echo '<span class="crd-star crd-star-empty">☆</span>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($header) : ?>
                <h3 class="crd-review-header"><?php echo esc_html($header); ?></h3>
            <?php endif; ?>

            <?php if ($body) : ?>
                <div class="crd-review-body">
                    <p><?php echo nl2br(esc_html($body)); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($name) : ?>
                <div class="crd-review-name">
                    <?php if ($profile_pic_id) :
                        $profile_pic_url = wp_get_attachment_image_url($profile_pic_id, 'thumbnail');
                        if ($profile_pic_url) : ?>
                            <img src="<?php echo esc_url($profile_pic_url); ?>" alt="<?php echo esc_attr($name); ?>" class="<?php echo esc_attr($profile_pic_class); ?>">
                        <?php endif;
                    endif; ?>
                    <strong class="crd-reviewer-name-text"><?php echo esc_html($name); ?></strong>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Generate inline styles
     */
    private function generate_inline_styles($instance_id, $settings, $columns) {
        $gap = isset($settings['gap_between_cards']) ? $settings['gap_between_cards'] : 20;
        $border_radius = isset($settings['border_radius']) ? $settings['border_radius'] : 10;
        $card_padding = isset($settings['card_padding']) ? $settings['card_padding'] : 20;
        $card_color = isset($settings['card_color']) ? $settings['card_color'] : '#ffffff';
        $card_shadow = isset($settings['card_shadow']) ? $settings['card_shadow'] : 1;

        $header_size = isset($settings['header_font_size']) ? $settings['header_font_size'] : 24;
        $body_size = isset($settings['body_font_size']) ? $settings['body_font_size'] : 16;
        $name_size = isset($settings['name_font_size']) ? $settings['name_font_size'] : 14;

        $header_font = !empty($settings['header_font_family']) ? $settings['header_font_family'] : 'inherit';
        $body_font = !empty($settings['body_font_family']) ? $settings['body_font_family'] : 'inherit';
        $name_font = !empty($settings['name_font_family']) ? $settings['name_font_family'] : 'inherit';

        $text_color = isset($settings['text_color']) ? $settings['text_color'] : '#333333';
        $header_color = isset($settings['header_color']) ? $settings['header_color'] : '#000000';
        $name_color = isset($settings['name_color']) ? $settings['name_color'] : '#666666';
        $rating_color = isset($settings['rating_color']) ? $settings['rating_color'] : '#ffa500';

        $border_enable = isset($settings['card_border_enable']) ? $settings['card_border_enable'] : 0;
        $border_width = isset($settings['card_border_width']) ? $settings['card_border_width'] : 1;
        $border_color = isset($settings['card_border_color']) ? $settings['card_border_color'] : '#e0e0e0';

        $mobile_columns = isset($settings['mobile_columns']) ? $settings['mobile_columns'] : 1;

        $rating_size = isset($settings['rating_font_size']) ? $settings['rating_font_size'] : 20;
        $profile_pic_size = isset($settings['profile_pic_size']) ? $settings['profile_pic_size'] : 50;
        $profile_pic_shape = isset($settings['profile_pic_shape']) ? $settings['profile_pic_shape'] : 'circle';
        $profile_pic_radius = $profile_pic_shape === 'circle' ? '50%' : '0';
        $name_vertical_offset = isset($settings['name_vertical_offset']) ? intval($settings['name_vertical_offset']) : 0;

        ?>
        <style>
            #<?php echo esc_attr($instance_id); ?> .crd-reviews-grid {
                display: grid;
                grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr);
                gap: <?php echo esc_attr($gap); ?>px;
                align-items: start;
            }

            #<?php echo esc_attr($instance_id); ?> .crd-review-card {
                background-color: <?php echo esc_attr($card_color); ?>;
                border-radius: <?php echo esc_attr($border_radius); ?>px;
                padding: <?php echo esc_attr($card_padding); ?>px;
                <?php if ($card_shadow) : ?>
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                <?php endif; ?>
                <?php if ($border_enable) : ?>
                border: <?php echo esc_attr($border_width); ?>px solid <?php echo esc_attr($border_color); ?>;
                <?php endif; ?>
            }

            #<?php echo esc_attr($instance_id); ?> .crd-review-header {
                font-size: <?php echo esc_attr($header_size); ?>px;
                color: <?php echo esc_attr($header_color); ?>;
                <?php if ($header_font !== 'inherit') : ?>
                font-family: <?php echo esc_attr($header_font); ?>;
                <?php endif; ?>
            }

            #<?php echo esc_attr($instance_id); ?> .crd-review-body {
                font-size: <?php echo esc_attr($body_size); ?>px;
                color: <?php echo esc_attr($text_color); ?>;
                <?php if ($body_font !== 'inherit') : ?>
                font-family: <?php echo esc_attr($body_font); ?>;
                <?php endif; ?>
            }

            #<?php echo esc_attr($instance_id); ?> .crd-review-name {
                font-size: <?php echo esc_attr($name_size); ?>px;
                color: <?php echo esc_attr($name_color); ?>;
                <?php if ($name_font !== 'inherit') : ?>
                font-family: <?php echo esc_attr($name_font); ?>;
                <?php endif; ?>
            }

            #<?php echo esc_attr($instance_id); ?> .crd-review-rating {
                font-size: <?php echo esc_attr($rating_size); ?>px;
            }

            #<?php echo esc_attr($instance_id); ?> .crd-star {
                color: <?php echo esc_attr($rating_color); ?>;
            }

            #<?php echo esc_attr($instance_id); ?> .crd-review-profile-pic {
                width: <?php echo esc_attr($profile_pic_size); ?>px;
                height: <?php echo esc_attr($profile_pic_size); ?>px;
                border-radius: <?php echo esc_attr($profile_pic_radius); ?>;
            }

            #<?php echo esc_attr($instance_id); ?> .crd-reviewer-name-text {
                margin-top: <?php echo esc_attr($name_vertical_offset); ?>px;
            }

            @media (max-width: 768px) {
                #<?php echo esc_attr($instance_id); ?> .crd-reviews-grid {
                    grid-template-columns: repeat(<?php echo esc_attr($mobile_columns); ?>, 1fr) !important;
                }
            }
        </style>
        <?php
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Only enqueue if shortcode is present
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'custom_reviews')) {
            wp_enqueue_style('crd-frontend-css', CRD_PLUGIN_URL . 'public/css/reviews-style.css', array(), CRD_VERSION);
        }
    }
}
