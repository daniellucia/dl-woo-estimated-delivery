<?php

namespace DL\EstimatedDelivery;

defined('ABSPATH') || exit;

final class Calendar
{

    private $weekdays = [];

    public function __construct()
    {
        $this->weekdays = [
            __('Mon', 'dl-woo-estimated-delivery'),
            __('Tue', 'dl-woo-estimated-delivery'),
            __('Wed', 'dl-woo-estimated-delivery'),
            __('Thu', 'dl-woo-estimated-delivery'),
            __('Fri', 'dl-woo-estimated-delivery'),
            __('Sat', 'dl-woo-estimated-delivery'),
            __('Sun', 'dl-woo-estimated-delivery')
        ];
    }

    public function get_weekdays()
    {
        return $this->weekdays;
    }

    /**
     * Renderiza el calendario
     * @param mixed $data
     * @return void
     * @author Daniel Lucia
     */
    public function render($data)
    {
        extract($data);

        echo '<div class="wrap">';

            echo '<h1>' . esc_html__('Estimated Delivery Options', 'dl-woo-estimated-delivery') . '</h1>';
            echo '<div id="dl-message"></div>';

            echo '<div id="dl-ed-calendar-container">';
                $prev_month = $month - 1;
                $prev_year = $year;
                if ($prev_month < 1) {
                    $prev_month = 12;
                    $prev_year--;
                }
                $next_month = $month + 1;
                $next_year = $year;
                if ($next_month > 12) {
                    $next_month = 1;
                    $next_year++;
                }
                echo '<div id="dl-ed-calendar-nav">';
                    echo '<a href="?page=dl-woo-estimated-delivery-settings&dl_ed_month=' . $prev_month . '&dl_ed_year=' . $prev_year . '" class="dl-ed-nav-link">&laquo; Anterior</a>';
                    echo '<span id="dl-ed-calendar-title">' . ucfirst(date_i18n('F Y', $first_day)) . '</span>';
                    echo '<a href="?page=dl-woo-estimated-delivery-settings&dl_ed_month=' . $next_month . '&dl_ed_year=' . $next_year . '" class="dl-ed-nav-link">Siguiente &raquo;</a>';
                echo '</div>';

                echo '<table id="dl-ed-calendar"><thead><tr>';

                foreach ($calendar->get_weekdays() as $wd) {
                    echo '<th>' . $wd . '</th>';
                }

                echo '</tr></thead><tbody><tr>';
                
                for ($i = 1; $i < $start_weekday; $i++) {
                    echo '<td></td>';
                }

                for ($day = 1; $day <= $days_in_month; $day++) {
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $is_sunday = (date('N', strtotime($date)) == 7);
                    $is_holiday = $is_sunday || in_array($date, $holidays);
                    $classes = 'dl-ed-day';
                    if ($is_holiday) $classes .= ' dl-ed-holiday';
                    if ($date == $today) $classes .= ' dl-ed-today';
                    echo '<td class="' . $classes . '" data-date="' . $date . '">' . $day . '</td>';
                    if (date('N', strtotime($date)) == 7 && $day != $days_in_month) echo '</tr><tr>';
                }

                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
                echo '<button id="dl-ed-save-holidays">' . __('Save holidays', 'dl-woo-estimated-delivery') . '</button>';
            echo '</div>';
        echo '</div>';
    }
}