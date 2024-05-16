<?php
namespace Devweb\DevWebEvent;

class Event_Date_Meta_Box {
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'event_date_meta_box']);
    }

    // Add meta box for event date
    function event_date_meta_box() {
        add_meta_box(
            'event_date_meta_box',
            'Event Date',
            [$this, 'event_date_meta_box_callback'],
            'devweb_events',
            'normal',
            'default'
        );
    }

    // Meta box callback function
    function event_date_meta_box_callback($post) {
        $event_date = get_post_meta($post->ID, '_event_date', true);
        $event_time = get_post_meta($post->ID, 'event_time', true);
        // Use nonce for verification
        wp_nonce_field('event_details_nonce', 'event_details_nonce');
        ?>
        <label for="event_date"> <?php echo __('Event Date:', 'dev-web') ?> </label>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>" required />
        <label for="event_time"> <?php echo __('Event Time:', 'dev-web') ?> </label>
        <input type="time" id="event_time" name="event_time" value="<?php echo esc_attr($event_time); ?>" required>
        <?php
    }

}