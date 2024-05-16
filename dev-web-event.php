<?php
/*
Plugin Name: Dev Web Event
Description: Manage events with custom post type and view.
Version: 1.0
Author: Rabiul Islam
Author URI: //rabiulislam.net
Text Domain: dev-web
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once (plugin_dir_path(__FILE__) . 'vendor/autoload.php');

class Dev_Web_event {

    public function __construct() {

        add_action('init', [$this, 'init']);

        // Hook into plugin activation
        register_activation_hook(__FILE__, array($this, 'activate'));

        // Hook into plugin deactivation
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Instantiate Register_Event_Post_Type class
        new Devweb\DevWebEvent\Register_Event_Post_Type();

        // Instantiate Event_Date_Meta_Box class
        new Devweb\DevWebEvent\Event_Date_Meta_Box();

        // Instantiate Save_Event_Date_Meta class
        new Devweb\DevWebEvent\Save_Event_Date_Meta();

        // Instantiate Custom_Event_Column class
        new Devweb\DevWebEvent\Custom_Event_Column();

        // Instantiate Event_Calendar_Admin_Page class
        new Devweb\DevWebEvent\Event_Calendar_Admin_Page();

        // Instantiate Display_Events_Shortcode class
        new Devweb\DevWebEvent\Display_Events_Shortcode();

    }

    function init() {

        add_action('admin_enqueue_scripts', [$this, 'enqueue_event_calendar_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_event_frontent_styles']);

    }

    // Enqueue custom CSS for the event calendar
    function enqueue_event_calendar_styles() {
        wp_enqueue_style('event-calendar-styles', plugin_dir_url(__FILE__) . '/assets/css/event-calendar.css');
    }

    // Enqueue custom CSS for frontend event show
    function enqueue_event_frontent_styles() {
        wp_enqueue_style('frontent-styles', plugin_dir_url(__FILE__) . '/assets/css/frontent-styles.css');
    }

    // Method to flush rewrite rules on activation
    public function activate() {
        $post_type = new Devweb\DevWebEvent\Register_Event_Post_Type();
        $post_type->register_event_post_type(); // Re-register custom post type
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
        $wp_rewrite->init();
    }

    // Method to flush rewrite rules on deactivation
    public function deactivate() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
        $wp_rewrite->init();
    }

}

new Dev_Web_event();