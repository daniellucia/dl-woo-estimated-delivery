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
            $calendar = new DL_Woo_Estimated_Delivery_Calendar();

            $month = isset($_GET['dl_ed_month']) ? intval($_GET['dl_ed_month']) : date('n');
            $year = isset($_GET['dl_ed_year']) ? intval($_GET['dl_ed_year']) : date('Y');
            $today = date('Y-m-d');
            $first_day = strtotime("$year-$month-01");
            $days_in_month = date('t', $first_day);
            $start_weekday = date('N', $first_day);
            $holidays = get_option('dl_estimated_delivery_holidays', []);
            $holidays = is_array($holidays) ? $holidays : [];

            $calendar->render([
                'calendar' => $calendar,
                'month' => $month,
                'year' => $year,
                'today' => $today,
                'first_day' => $first_day,
                'days_in_month' => $days_in_month,
                'start_weekday' => $start_weekday,
                'holidays' => $holidays,
            ]);
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
