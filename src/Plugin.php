<?php

defined('ABSPATH') || exit;

if (! class_exists('DL_Woo_Estimated_Delivery')) {

    final class DL_Woo_Estimated_Delivery
    {

        public function init()
        {

            $product = new DL_Woo_Estimated_Delivery_Product();
            add_action('woocommerce_product_options_shipping', [$product, 'add_preparation_days_field']);
            add_action('woocommerce_process_product_meta', [$product, 'save_preparation_days_field']);

            $plugin = new DL_Woo_Estimated_Delivery_Config();
            add_action('admin_menu', [$plugin, 'add_admin_menu']);
            add_action('wp_ajax_dl_ed_save_holidays', [$plugin, 'saveData']);
            add_action('admin_enqueue_scripts', [$plugin, 'enqueueScripts']);

            add_action('woocommerce_review_order_before_submit', [$this, 'show_estimated_delivery'], 20);
            add_action('woocommerce_proceed_to_checkout', [$this, 'show_estimated_delivery_cart'], 20);
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
            add_action('woocommerce_single_product_summary', [$this, 'show_estimated_delivery_product'], 21);
        }

        private function calculateDeliveryDate(int $product_id = 0)
        {

            $preparation_days = 0;
            if ($product_id > 0) {
                $preparation_days = get_post_meta($product_id, '_dl_preparation_days', true);
            }

            $min_days = 3 + $preparation_days;
            $max_days = 5 + $preparation_days;

            return [
                'min' => date_i18n(get_option('date_format'), strtotime("+$min_days days")),
                'max' => date_i18n(get_option('date_format'), strtotime("+$max_days days")),
            ];
        }

        private function render(int $product_id = 0)
        {
            $delivery_dates = $this->calculateDeliveryDate($product_id);
            $min_date = $delivery_dates['min'];
            $max_date = $delivery_dates['max'];

            echo '<p class="dl-estimated-delivery">';
            printf(
                esc_html__('Fecha estimada de entrega: entre %1$s y %2$s', 'dl-woo-estimated-delivery'),
                esc_html($min_date),
                esc_html($max_date)
            );
            echo '</p>';
        }

        public function show_estimated_delivery_product()
        {
            global $product;
            if ($product && $product->is_type('simple')) {
                $this->render($product->get_id());
            } else {
                $this->render();
            }
        }

        public function show_estimated_delivery()
        {

            $this->render();
        }

        public function show_estimated_delivery_cart()
        {
            $this->render();
        }

        /**
         * Encolar scripts para checkout con bloques
         */
        public function enqueue_scripts()
        {

            wp_enqueue_script(
                'dl-est-delivery-checkout',
                plugin_dir_url(DL_WOO_ESTIMATED_DELIVERY_FILE) . 'assets/js/delivery-checkout.js',
                [],
                DL_WOO_ESTIMATED_DELIVERY_VERSION,
                true
            );

            $delivery_dates = $this->calculateDeliveryDate();
            wp_localize_script('dl-est-delivery-checkout', 'dl_estimated_delivery', [
                'estimatedDelivery' => sprintf(
                    esc_html__('Fecha estimada de entrega: entre %1$s y %2$s', 'dl-woo-estimated-delivery'),
                    esc_html($delivery_dates['min']),
                    esc_html($delivery_dates['max'])
                ),
            ]);
        }
    }

    new DL_Woo_Estimated_Delivery();
}
