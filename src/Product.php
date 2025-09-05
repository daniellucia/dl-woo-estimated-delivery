<?php

namespace DL\EstimatedDelivery;

defined('ABSPATH') || exit;

final class Product
{

    /**
     * Añadimos un campo al producto para los días de preparación
     * @return void
     * @author Daniel Lucia
     */
    public function add_preparation_days_field()
    {
        woocommerce_wp_text_input([
            'id' => '_dl_preparation_days',
            'label' => __('Days of preparation', 'dl-woo-estimated-delivery'),
            'desc_tip' => true,
            'description' => __('Number of days required to prepare the product before shipping.', 'dl-woo-estimated-delivery'),
            'type' => 'number',
            'custom_attributes' => [
                'min' => '0',
                'step' => '1'
            ]
        ]);
    }

    /**
     * Guardamos los dias de preparación
     * @param mixed $post_id
     * @return void
     * @author Daniel Lucia
     */
    public function save_preparation_days_field($post_id)
    {
        $value = isset($_POST['_dl_preparation_days']) ? intval($_POST['_dl_preparation_days']) : '';
        update_post_meta($post_id, '_dl_preparation_days', $value);
    }
}
