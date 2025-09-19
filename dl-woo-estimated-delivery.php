<?php

/**
 * Plugin Name: Estimated Delivery for WooCommerce
 * Plugin URI:  http://daniellucia.es
 * Description: Displays the estimated delivery date in the WooCommerce checkout.
 * Version:     0.0.2
 * Author:      Daniel Lucia
 * Author URI:  http://daniellucia.es
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dl-woo-estimated-delivery
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

/*
Copyright (C) 2025  Daniel Lucia (https://daniellucia.es)

Este programa es software libre: puedes redistribuirlo y/o modificarlo
bajo los términos de la Licencia Pública General GNU publicada por
la Free Software Foundation, ya sea la versión 2 de la Licencia,
o (a tu elección) cualquier versión posterior.

Este programa se distribuye con la esperanza de que sea útil,
pero SIN NINGUNA GARANTÍA; ni siquiera la garantía implícita de
COMERCIABILIDAD o IDONEIDAD PARA UN PROPÓSITO PARTICULAR.
Consulta la Licencia Pública General GNU para más detalles.

Deberías haber recibido una copia de la Licencia Pública General GNU
junto con este programa. En caso contrario, consulta <https://www.gnu.org/licenses/gpl-2.0.html>.
*/

use DL\EstimatedDelivery\Plugin;

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

define('DL_WOO_ESTIMATED_DELIVERY_VERSION', '0.0.2');
define('DL_WOO_ESTIMATED_DELIVERY_FILE', __FILE__);

add_action('init', function () {

    load_plugin_textdomain('dl-woo-estimated-delivery', false, dirname(plugin_basename(__FILE__)) . '/languages');

    $plugin = new Plugin();
    $plugin->init();
});

/**
 * Limpiamos caché al activar o desactivar el plugin
 */
register_activation_hook(__FILE__, function() {
    wp_cache_flush();
});

register_deactivation_hook(__FILE__, function() {
    wp_cache_flush();
});