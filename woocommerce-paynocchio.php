<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cfps.co
 * @since             1.0.0
 * @package           Woocommerce_Paynocchio
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Paynocchio Payment Gateway
 * Plugin URI:        https://cfps.co
 * Description:       Official Paynocchio payment plugin for Woocommerce.
 * Version:           1.0.0
 * Author:            CFP TECHNOLOGY
 * Author URI:        https://cfps.co/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-paynocchio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOOCOMMERCE_PAYNOCCHIO_VERSION', '1.0.0' );

/**
 * Paynocchio page activation slug
 */
define( 'WOOCOMMERCE_PAYNOCCHIO_ACTIVATION_PAGE_SLUG', 'paynocchio-activation' );

/**
 * Paynocchio params names
 */
const PAYNOCCHIO_USER_UUID_KEY = 'user_uuid';
const PAYNOCCHIO_ENV_KEY = 'environment_uuid';
const PAYNOCCHIO_CURRENCY_KEY = 'currency_uuid';
const PAYNOCCHIO_TYPE_KEY = 'type_uuid';
const PAYNOCCHIO_STATUS_KEY = 'status_uuid';
const PAYNOCCHIO_SECRET_KEY = 'secret_uuid';

if ( ! defined( 'WOOCOMMERCE_PAYNOCCHIO_PAGE_FILE' ) ) {
    define( 'WOOCOMMERCE_PAYNOCCHIO_PAGE_FILE', __FILE__ );
}

if ( ! defined( 'WOOCOMMERCE_PAYNOCCHIO_PATH' ) ) {
    define( 'WOOCOMMERCE_PAYNOCCHIO_PATH', plugin_dir_path( WOOCOMMERCE_PAYNOCCHIO_PAGE_FILE ) );
}

if ( ! defined( 'WOOCOMMERCE_PAYNOCCHIO_BASENAME' ) ) {
    define( 'WOOCOMMERCE_PAYNOCCHIO_BASENAME', plugin_basename( WOOCOMMERCE_PAYNOCCHIO_PAGE_FILE ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-paynocchio-activator.php
 */
function activate_woocommerce_paynocchio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-paynocchio-activator.php';
	Woocommerce_Paynocchio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-paynocchio-deactivator.php
 */
function deactivate_woocommerce_paynocchio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-paynocchio-deactivator.php';
	Woocommerce_Paynocchio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_paynocchio' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_paynocchio' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-paynocchio.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_paynocchio() {

	$plugin = new Woocommerce_Paynocchio();
	$plugin->run();

}
run_woocommerce_paynocchio();
