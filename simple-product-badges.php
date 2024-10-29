<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Simple Product Badges
 * Plugin URI:        https://github.com/Sirvelia/simple-product-badges
 * Description:       Create simple customized badges for your store products in WooCommerce.
 * Version:           1.1.1
 * Author:            Sirvelia
 * Author URI:        https://sirvelia.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       simple-product-badges
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

// Direct access, abort.
if (!defined('WPINC')) {
	die('YOU SHALL NOT PASS!');
}

define('SIMPLEPRODUCTBADGES_VERSION', '1.1.1');
define('SIMPLEPRODUCTBADGES_PATH', plugin_dir_path(__FILE__));
define('SIMPLEPRODUCTBADGES_BASENAME', plugin_basename(__FILE__));
define('SIMPLEPRODUCTBADGES_URL', plugin_dir_url(__FILE__));

require_once SIMPLEPRODUCTBADGES_PATH . 'vendor/autoload.php';

register_activation_hook(__FILE__, function () {
	SimpleProductBadges\Includes\Activator::activate();
});

register_deactivation_hook(__FILE__, function () {
	SimpleProductBadges\Includes\Deactivator::deactivate();
});

//LOAD ALL PLUGIN FILES
$loader = new SimpleProductBadges\Includes\Loader();