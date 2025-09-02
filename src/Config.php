<?php


defined('ABSPATH') || exit;

if (! class_exists('DL_Woo_Estimated_Delivery_Config')) {

    final class DL_Woo_Estimated_Delivery_Config
    {

        /**
         * Añadimos el menú de administración
         * @return void
         * @author Daniel Lucia
         */
        public function add_admin_menu()
        {
            add_submenu_page(
                'woocommerce',
                __('Estimated Delivery Options', 'dl-woo-estimated-delivery'),
                __('Estimated Delivery', 'dl-woo-estimated-delivery'),
                'manage_options',
                'dl-woo-estimated-delivery-settings',
                [$this, 'render_options_page']
            );
        }

        /**
         * Renderizamos la página de opciones
         * @return void
         * @author Daniel Lucia
         */
        public function render_options_page()
        {
            $month = isset($_GET['dl_ed_month']) ? intval($_GET['dl_ed_month']) : date('n');
            $year = isset($_GET['dl_ed_year']) ? intval($_GET['dl_ed_year']) : date('Y');
            $today = date('Y-m-d');
            $first_day = strtotime("$year-$month-01");
            $days_in_month = date('t', $first_day);
            $start_weekday = date('N', $first_day);
            $holidays = get_option('dl_estimated_delivery_holidays', []);
            $holidays = is_array($holidays) ? $holidays : [];
            echo '<div class="wrap"><h1>' . esc_html__('Estimated Delivery Options', 'dl-woo-estimated-delivery') . '</h1>';
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
            echo '<span id="dl-ed-calendar-title">' . date_i18n('F Y', $first_day) . '</span>';
            echo '<a href="?page=dl-woo-estimated-delivery-settings&dl_ed_month=' . $next_month . '&dl_ed_year=' . $next_year . '" class="dl-ed-nav-link">Siguiente &raquo;</a>';
            echo '</div>';
            echo '<table id="dl-ed-calendar"><thead><tr>';

            $weekdays = [
                __('Mon', 'dl-woo-estimated-delivery'), 
                __('Tue', 'dl-woo-estimated-delivery'), 
                __('Wed', 'dl-woo-estimated-delivery'), 
                __('Thu', 'dl-woo-estimated-delivery'), 
                __('Fri', 'dl-woo-estimated-delivery'), 
                __('Sat', 'dl-woo-estimated-delivery'), 
                __('Sun', 'dl-woo-estimated-delivery')
            ];

            foreach ($weekdays as $wd) echo '<th>' . $wd . '</th>';
            echo '</tr></thead><tbody><tr>';
            for ($i = 1; $i < $start_weekday; $i++) echo '<td></td>';
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
            echo '</tr></tbody></table>';
            echo '<button id="dl-ed-save-holidays">Guardar festivos</button>';
            echo '</div>';
            echo '</div>';
        }

        /**
         * Guardamos los días festivos
         * @return void
         * @author Daniel Lucia
         */
        public function saveData()
        {
            if (!current_user_can('manage_options')) wp_send_json_error('No autorizado');
            $new_holidays = isset($_POST['holidays']) ? (array)$_POST['holidays'] : [];
            $saved_holidays = get_option('dl_estimated_delivery_holidays', []);
            $saved_holidays = is_array($saved_holidays) ? $saved_holidays : [];
            $merged = array_unique(array_merge($saved_holidays, $new_holidays));
            update_option('dl_estimated_delivery_holidays', $merged);
            wp_send_json_success('Guardado');
        }

        /**
         * Encolamos los scripts necesarios para la configuración
         * @return void
         * @author Daniel Lucia
         */
        public function enqueueScripts()
        {
            if (isset($_GET['page']) && $_GET['page'] === 'dl-woo-estimated-delivery-settings') {
                wp_enqueue_style('dl-ed-calendar', plugin_dir_url(DL_WOO_ESTIMATED_DELIVERY_FILE) . 'assets/css/delivery-calendar.css', [], DL_WOO_ESTIMATED_DELIVERY_VERSION);
                wp_enqueue_script('dl-ed-calendar', plugin_dir_url(DL_WOO_ESTIMATED_DELIVERY_FILE) . 'assets/js/delivery-calendar.js', ['jquery'], DL_WOO_ESTIMATED_DELIVERY_VERSION, true);

                wp_localize_script('dl-ed-calendar', 'dlEdCalendar', [
                    'dlEdAjaxUrl' => admin_url('admin-ajax.php'),
                ]);
            }
        }
    }
}
