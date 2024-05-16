<?php
namespace Devweb\DevWebEvent;

class Register_Event_Post_Type {
    public function __construct() {
        add_action('init', [$this, 'register_event_post_type']);
    }

    // Register Event Post Type
    function register_event_post_type() {
        $labels = array(
            'name' => __('Events', 'dev-web'),
            'singular_name' => __('Event', 'dev-web'),
            'add_new' => __('Add New Event', 'dev-web'),
            'add_new_item' => __('Add New Event', 'dev-web'),
            'edit_item' => __('Edit Event', 'dev-web'),
            'new_item' => __('New Event', 'dev-web'),
            'all_items' => __('All Events', 'dev-web'),
            'view_item' => __('View Event', 'dev-web'),
            'search_items' => __('Search Events', 'dev-web'),
            'not_found' => __('No events found', 'dev-web'),
            'not_found_in_trash' => __('No events found in Trash', 'dev-web'),
            'menu_name' => __('Dev Web Events', 'dev-web')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor'),
            'menu_icon' => 'dashicons-calendar-alt',
            'rewrite' => array(
                'slug' => 'devweb-events',
                'with_front' => false
            )
        );

        register_post_type('devweb_events', $args);
    }

}