<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://skewerspot.com/
 * @since             1.0.0
 * @package           MyRestaurantReviews
 *
 * @wordpress-plugin
 * Plugin Name:       My Restaurant Reviews
 * Plugin URI:        https://skewerspot.com/reviews-wp-plugin/
 * Description:       Displays public ratings and reviews of your restaurant from online sources such as Zomato and Google.
 * Version:           1.0.0
 * Author:            Anurag Bhandari
 * Author URI:        https://anuragbhandari.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ssmrr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define( 'MRR_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mrr-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mrr-activator.php';
	MyRestaurantReviewsActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mrr-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mrr-deactivator.php';
	MyRestaurantReviewsDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mrr.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mrr() {

	$plugin = new MyRestaurantReviews();
	$plugin->run();

}
run_mrr();
