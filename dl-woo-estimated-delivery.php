<?php

/**
 * Plugin Name: Estimated Delivery for WooCommerce
 * Plugin URI:  http://daniellucia.es
 * Description: Displays the estimated delivery date in the WooCommerce checkout.
 * Version:     0.0.2
 * Author:      Daniel Lucia
 * Author URI:  http://daniellucia.es
 * License:     GPLv2 or later
 * Text Domain: dl-woo-estimated-delivery
 * Domain Path: /languages
 * Requires Plugins: WooCommerce
 */

use DL\EstimatedDelivery\Plugin;

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

define('DL_WOO_ESTIMATED_DELIVERY_VERSION', '0.0.2');
define('DL_WOO_ESTIMATED_DELIVERY_FILE', __FILE__);

add_action('plugins_loaded', function () {

    load_plugin_textdomain('dl-woo-estimated-delivery', false, dirname(plugin_basename(__FILE__)) . '/languages');

    (new Plugin())->init();
});
