<?php


defined('ABSPATH') || exit;

if (! class_exists('DL_Woo_Estimated_Delivery_Calendar')) {

    final class DL_Woo_Estimated_Delivery_Calendar
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
    }
}
