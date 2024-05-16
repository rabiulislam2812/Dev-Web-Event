<?php
namespace Devweb\DevWebEvent;


class Save_Event_Date_Meta {
    public function __construct() {
        add_action('save_post_devweb_events', [$this, 'save_date_meta']);
    }

    //Save event date meta
    function save_date_meta($post_id) {
        // Check if nonce is set
        if (!isset($_POST['event_details_nonce'])) {
            return;
        }

        // Verify nonce
        if (!wp_verify_nonce($_POST['event_details_nonce'], 'event_details_nonce')) {
            return;
        }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['event_date'])) {
            $event_date = sanitize_text_field($_POST['event_date']);
            update_post_meta($post_id, '_event_date', $event_date);
        }

        if (isset($_POST['event_time'])) {
            update_post_meta($post_id, 'event_time', sanitize_text_field($_POST['event_time']));
        }
    }
}