<?php
/*
Plugin Name: Post View Counter
Plugin URI: https://example.com/
Description: Tracks the number of views for each post and displays the view count in the posts page of the dashboard.
Version: 1.0
Author: Your Name
Author URI: https://example.com/
*/

// Function to increment the post view count
function increment_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    if ($count === '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

// Function to retrieve the post view count
function get_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    if ($count === '') {
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
        return "0 View";
    }
    return $count . ' View' . ($count !== '1' ? 's' : '');
}

// Action to increment the post view count when a post is viewed
function track_post_views() {
    if (is_single()) {
        global $post;
        increment_post_views($post->ID);
    }
}
add_action('wp_head', 'track_post_views');

// Function to add the post view count column to the posts page in the dashboard
function add_post_views_column($defaults) {
    $defaults['post_views'] = 'Views';
    return $defaults;
}
add_filter('manage_posts_columns', 'add_post_views_column');

// Function to display the post view count in the posts page of the dashboard
function display_post_views_count($column_name, $post_id) {
    if ($column_name === 'post_views') {
        echo get_post_views($post_id);
    }
}
add_action('manage_posts_custom_column', 'display_post_views_count', 10, 2);