<?php
namespace Devweb\DevWebEvent;

use WP_Query;

class Display_Events_Shortcode {
    public function __construct() {
        add_shortcode('dev_web_display_events', [$this, 'display_events_shortcode']);
    }

    // Shortcode to display events
    function display_events_shortcode($atts) {
        $atts = shortcode_atts(array(
            'month' => date('m'),
            'year' => date('Y'),
        ), $atts);

        // Parse shortcode attributes
        $month = intval($atts['month']);
        $year = intval($atts['year']);

        // Query events for the specified month and year
        $events_query = new WP_Query(array(
            'post_type' => 'devweb_events',
            'posts_per_page' => -1, // Retrieve all events
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_event_date',
                    'value' => array("{$year}-{$month}-01", "{$year}-{$month}-31"),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ),
            ),
            'orderby' => 'date',
            // 'meta_key' => '_event_date',
            'order' => 'DESC',
        ));

        // Start output buffer
        ob_start();

        if ($events_query->have_posts()) {
            echo '<div class="event-list">';
            while ($events_query->have_posts()) {
                $events_query->the_post();
                $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                $formatted_date = date('F j, Y', strtotime($event_date));
                $event_time = get_post_meta(get_the_ID(), 'event_time', true);
                $formatted_time = date('h:i A', strtotime($event_time));
                ?>
                <div class="event">
                    <h2><a href="<?php esc_attr(the_permalink()) ?>"><?php esc_html(the_title()); ?></a></h2>
                    <p>
                        <strong> <?php echo __('Date:', 'dev-web') ?> </strong>
                        <?php echo esc_html($formatted_date); ?>
                    </p>
                    <p class="event-time">
                        <strong> <?php echo __('Time:', 'dev-web') ?> </strong>
                        <?php echo esc_html($formatted_time); ?>
                    </p>
                </div>
                <?php
            }
            echo '</div>';
            // Restore original post data
            wp_reset_postdata();
        } else {
            echo __('<h1>No event found.</h1>', 'dev-web');
        }

        // Return buffered content
        return ob_get_clean();
    }


}

