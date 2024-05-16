<?php
namespace Devweb\DevWebEvent;

class Custom_Event_Column {
    public function __construct() {
        add_filter('manage_devweb_events_posts_columns', [$this, 'add_custom_event_column']);
        add_action('manage_devweb_events_posts_custom_column', [$this, 'populate_event_date_column'], 10, 2);
        add_filter('manage_edit-devweb_events_sortable_columns', [$this, 'make_event_date_column_sortable']);
        add_action('pre_get_posts', [$this, 'handle_event_date_column_sorting']);
    }

    // Add custom admin column for event date
    function add_custom_event_column($columns) {
        unset($columns['date']);
        $columns['event_date'] = 'Event Date';
        $columns['date'] = 'Date';

        return $columns;
    }

    // Populate custom admin column with event date
    function populate_event_date_column($column, $post_id) {
        if ($column === 'event_date') {
            $event_date = get_post_meta($post_id, '_event_date', true);
            echo esc_html($event_date);
        }
    }

    // Make custom admin column sortable
    function make_event_date_column_sortable($columns) {
        $columns['event_date'] = 'event_date';
        return $columns;
    }

    // Modify query to handle sorting by event date
    function handle_event_date_column_sorting($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $orderby = $query->get('orderby');

        if ('event_date' === $orderby) {
            $query->set('meta_key', '_event_date');
            $query->set('orderby', 'meta_value');
        }
    }
}