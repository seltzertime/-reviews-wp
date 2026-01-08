<?php
/**
 * Custom Post Type for Reviews
 *
 * @package Custom_Reviews_Display
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class CRD_Reviews_CPT {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
    }

    /**
     * Register custom post type for reviews
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Reviews', 'Post type general name', 'custom-reviews-display'),
            'singular_name'         => _x('Review', 'Post type singular name', 'custom-reviews-display'),
            'menu_name'             => _x('Reviews', 'Admin Menu text', 'custom-reviews-display'),
            'name_admin_bar'        => _x('Review', 'Add New on Toolbar', 'custom-reviews-display'),
            'add_new'               => __('Add New', 'custom-reviews-display'),
            'add_new_item'          => __('Add New Review', 'custom-reviews-display'),
            'new_item'              => __('New Review', 'custom-reviews-display'),
            'edit_item'             => __('Edit Review', 'custom-reviews-display'),
            'view_item'             => __('View Review', 'custom-reviews-display'),
            'all_items'             => __('All Reviews', 'custom-reviews-display'),
            'search_items'          => __('Search Reviews', 'custom-reviews-display'),
            'parent_item_colon'     => __('Parent Reviews:', 'custom-reviews-display'),
            'not_found'             => __('No reviews found.', 'custom-reviews-display'),
            'not_found_in_trash'    => __('No reviews found in Trash.', 'custom-reviews-display'),
            'featured_image'        => _x('Review Image', 'Overrides the "Featured Image" phrase', 'custom-reviews-display'),
            'set_featured_image'    => _x('Set review image', 'Overrides the "Set featured image" phrase', 'custom-reviews-display'),
            'remove_featured_image' => _x('Remove review image', 'Overrides the "Remove featured image" phrase', 'custom-reviews-display'),
            'use_featured_image'    => _x('Use as review image', 'Overrides the "Use as featured image" phrase', 'custom-reviews-display'),
            'archives'              => _x('Review archives', 'The post type archive label', 'custom-reviews-display'),
            'insert_into_item'      => _x('Insert into review', 'Overrides the "Insert into post" phrase', 'custom-reviews-display'),
            'uploaded_to_this_item' => _x('Uploaded to this review', 'Overrides the "Uploaded to this post" phrase', 'custom-reviews-display'),
            'filter_items_list'     => _x('Filter reviews list', 'Screen reader text', 'custom-reviews-display'),
            'items_list_navigation' => _x('Reviews list navigation', 'Screen reader text', 'custom-reviews-display'),
            'items_list'            => _x('Reviews list', 'Screen reader text', 'custom-reviews-display'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-star-filled',
            'supports'           => array('title'),
            'show_in_rest'       => false,
        );

        register_post_type('crd_review', $args);
    }
}
