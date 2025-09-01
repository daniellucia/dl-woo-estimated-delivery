<?php


defined('ABSPATH') || exit;

if (! class_exists('DL_Woo_Estimated_Delivery_Product')) {

    final class DL_Woo_Estimated_Delivery_Product
    {

        public function add_preparation_days_field()
        {
            woocommerce_wp_text_input([
                'id' => '_dl_preparation_days',
                'label' => __('Días de preparación', 'dl-woo-estimated-delivery'),
                'desc_tip' => true,
                'description' => __('Cantidad de días necesarios para preparar el producto antes de enviarlo.', 'dl-woo-estimated-delivery'),
                'type' => 'number',
                'custom_attributes' => [
                    'min' => '0',
                    'step' => '1'
                ]
            ]);
        }

        public function save_preparation_days_field($post_id)
        {
            $value = isset($_POST['_dl_preparation_days']) ? intval($_POST['_dl_preparation_days']) : '';
            update_post_meta($post_id, '_dl_preparation_days', $value);
        }
    }
}
