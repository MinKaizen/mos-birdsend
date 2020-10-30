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
 * @package           MOS/Birdsend
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

namespace MOS\Birdsend;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Constants
 */

define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );
define( NS . 'PLUGIN_NAME', 'mos-birdsend' );
define( NS . 'PLUGIN_VERSION', '1.0.0' );
define( NS . 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( NS . 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( NS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

class MosBirdsendPlugin {

	public function init() {
		$this->load_dependencies();
		$this->register_actions();
	}

	private function load_dependencies() {
		require( PLUGIN_DIR . '/inc/config.php' );
		require( PLUGIN_DIR . '/inc/functions.php' );
		require( PLUGIN_DIR . '/inc/ClickbankEventAdapter.php' );
	}

	private function register_actions() {
		// User Activation -> Subscribe to Birdsend (Members Sequence)
		\MOS\Async\add_action_async( 'gform_activate_user', NS.'_on_user_activate' ); 
		
		// Clickbank sale -> Subscribe to Birdsend (Partners Sequence)
		\MOS\Async\add_action_async( 'clickbank_sale', NS.'_on_clickbank_sale' ); 
		\MOS\Async\add_action_async( 'clickbank_test_sale', NS.'_on_clickbank_sale' ); 
	}

}

require( PLUGIN_DIR . '/inc/Activator.php' );
if ( Activator::ok_to_init() ) {
	$mos_birdsend_plugin = new MosBirdsendPlugin();
	$mos_birdsend_plugin->init();
} else {
	return;
}

// /**
//  * #TEST
//  * 
//  * Instructions:
//  * 1. Uncomment this
//  * 2. Go to functions.php > _on_user_activate()
//  * 3. Replace log at the end with a print_r
//  * 
//  * Test url: /wp-json/mos/v1/test-birdsend
//  * Expected result: prints responses (like a log file)
//  */
// // Flush rewrite rules on init so that we don't have to keep
// // doing it manually while testing
// \add_action( 'init', function() {
//   \flush_rewrite_rules(false);
// } );
// // Add rest route
// \add_action( 'rest_api_init', function () {
//   \register_rest_route('mos/v1', 'test-birdsend', [
//     'methods' => 'GET',
//     'callback' => function () {
// 			$user_id = 443093;
// 			_on_user_activate( $user_id );
// 		},
//   ] );
// } );