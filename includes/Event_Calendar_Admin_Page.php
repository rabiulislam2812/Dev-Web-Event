<?php
namespace Devweb\DevWebEvent;

use WP_Query;

class Event_Calendar_Admin_Page {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_event_calendar_admin_page']);
    }

    // Add Event Calendar Admin Page
    function add_event_calendar_admin_page() {
        add_submenu_page(
            'edit.php?post_type=devweb_events',
            'Event Calendar',
            'Event Calendar',
            'manage_options',
            'event-calendar',
            [$this, 'display_event_calendar'],
        );
    }

    // Display custom admin page with calendar and pagination
    function display_event_calendar() {
        // Parse query parameters
        $current_month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $current_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

        // Adjust month and year for previous and next links
        $prev_month = $current_month - 1;
        $prev_year = $current_year;
        if ($prev_month < 1) {
            $prev_month = 12;
            $prev_year--;
        }

        $next_month = $current_month + 1;
        $next_year = $current_year;
        if ($next_month > 12) {
            $next_month = 1;
            $next_year++;
        }

        // Output calendar HTML
        ?>
        <div class="calender-wrap">
            <h1>Event Calendar</h1>
            <div class="calendar-navigation">
                <a
                    href="?post_type=devweb_events&page=event-calendar&month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>">
                    &laquo; <?php echo __('PreviousMonth', 'dev-web') ?> </a>
                <span><?php echo date('F Y', strtotime("{$current_year}-{$current_month}-01")); ?></span>
                <a
                    href="?post_type=devweb_events&page=event-calendar&month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>">
                    <?php echo __('Next Month', 'dev-web') ?> &raquo;</a>
            </div>
            <table class="event-calendar">
                <caption><?php echo date('F Y', strtotime("{$current_year}-{$current_month}-01")); ?></caption>
                <tr>
                    <th> <?php echo __('SUN', 'dev-web') ?> </th>
                    <th> <?php echo __('MON', 'dev-web') ?> </th>
                    <th> <?php echo __('TUE', 'dev-web') ?> </th>
                    <th> <?php echo __('WED', 'dev-web') ?> </th>
                    <th> <?php echo __('THU', 'dev-web') ?> </th>
                    <th> <?php echo __('FRI', 'dev-web') ?> </th>
                    <th> <?php echo __('SAT', 'dev-web') ?> </th>
                </tr>
                <?php
                // Get the first day of the month
                $first_day_timestamp = strtotime("{$current_year}-{$current_month}-01");
                $first_day_of_week = date('N', $first_day_timestamp);
                $days_in_month = date('t', $first_day_timestamp);

                echo '<tr>';

                // Fill in the days of the week before the first day of the month
                for ($i = 1; $i < $first_day_of_week; $i++) {
                    echo '<td></td>';
                }

                // Loop through each day of the month
                for ($day = 1; $day <= $days_in_month; $day++) {
                    $date = sprintf('%d-%02d-%02d', $current_year, $current_month, $day);
                    $events_query = new WP_Query(array(
                        'post_type' => 'devweb_events',
                        'meta_query' => array(
                            array(
                                'key' => '_event_date',
                                'value' => $date,
                                'compare' => '='
                            )
                        )
                    ));

                    $event_count = $events_query->found_posts;

                    if ($event_count > 0) {

                        echo '<td class="calendar-day">';
                        echo '<span class="event-day">' . $day . '</span><br>';

                        // Display event titles with permalinks
                        if ($events_query->have_posts()) {
                            echo '<ul class="event-titles">';
                            while ($events_query->have_posts()) {
                                $events_query->the_post();
                                $event_title = get_the_title();
                                $event_permalink = get_permalink();
                                echo '<li><a href="' . esc_url($event_permalink) . '">' . esc_html(wp_trim_words($event_title, 10)) . ' &raquo;</a></li>';
                            }
                            echo '</ul>';
                        }

                        echo '</td>';
                    } else {
                        echo '<td class="calendar-day">';
                        echo '<strong>' . $day . '</strong><br>';

                        echo '</td>';
                    }

                    // Start new row for next week
                    if ($first_day_of_week % 7 == 0 && $day != $days_in_month) {
                        echo '</tr><tr>';
                    }

                    $first_day_of_week++;
                }

                echo '</tr>';
                ?>
            </table>
        </div>
        <?php
    }


}

