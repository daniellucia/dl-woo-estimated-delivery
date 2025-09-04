<?php

/**
 * Plugin Name: Estimated Delivery for WooCommerce
 * Plugin URI:  http://daniellucia.es
 * Description: Muestra la fecha estimada de entrega en el checkout de WooCommerce.
 * Version:     0.0.2
 * Author:      Daniel Lucia
 * Author URI:  http://daniellucia.es
 * License:     GPLv2 or later
 * Text Domain: dl-woo-estimated-delivery
 * Domain Path: /languages
 * Requires Plugins: WooCommerce
 */

defined('ABSPATH') || exit;

define('DL_WOO_ESTIMATED_DELIVERY_VERSION', '0.0.2');
define('DL_WOO_ESTIMATED_DELIVERY_FILE', __FILE__);

add_action('plugins_loaded', function () {

    require_once __DIR__ . '/src/Plugin.php';
    require_once __DIR__ . '/src/Product.php';
    require_once __DIR__ . '/src/Config.php';
    require_once __DIR__ . '/src/Calendar.php';
    require_once __DIR__ . '/src/Days.php';

    load_plugin_textdomain('dl-woo-estimated-delivery', false, dirname(plugin_basename(__FILE__)) . '/languages');

    $plugin = new DL_Woo_Estimated_Delivery();
    $plugin->init();
});
