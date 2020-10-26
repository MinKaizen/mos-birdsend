<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://myonlinestartup.com
 * @since             1.0.0
 * @package           MOS_Birdsend
 *
 * @wordpress-plugin
 * Plugin Name:       MOS Birdsend
 * Plugin URI:        http://myonlinestartup.com/
 * Description:       Birdsend API client
 * Version:           1.0.0
 * Author:            My Online Startup
 * Author URI:        http://myonlinestartup.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mos-birdsend
 * Domain Path:       /languages
 */

namespace MOS_Birdsend;

use MOS_Birdsend\Router;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Constants
 */

define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );

define( 'PLUGIN_NAME', 'mos-birdsend' );

define( 'PLUGIN_VERSION', '1.0.0' );

define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );


class MOS_Birdsend {

	public function init() {
		$this->load_dependencies();
		$this->register_actions();
	}

	private function load_dependencies() {
		require( PLUGIN_DIR . '/inc/Handlers.php' );
		require( PLUGIN_DIR . '/inc/Routes.php' );
	}

	private function register_actions() {
		\add_action( 'rest_api_init', 'MOS_Birdsend\Routes\test_route');
	}

	public function test_method() {
		return "Hello world!";
	}

}

$min_php = '5.6.0';

// Check the minimum required PHP version and run the plugin.
if ( version_compare( PHP_VERSION, $min_php, '>=' ) ) {
	$mos_birdsend_plugin = new MOS_Birdsend();
	$mos_birdsend_plugin->init();
}