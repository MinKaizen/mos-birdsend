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

// require( PLUGIN_DIR . '/inc/activate.php' );
// \register_activation_hook( __FILE__, '\MOS\Birdsend\Activate\_on_activate' );

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
		\MOS\Async\add_action_async( 'gform_activate_user', NS.'subscribe_to_mos_members' ); 
		
		// Clickbank sale -> Subscribe to Birdsend (Partners Sequence)
		\MOS\Async\add_action_async( 'clickbank_sale', NS.'_on_clickbank_sale' ); 
		\MOS\Async\add_action_async( 'clickbank_test_sale', NS.'_on_clickbank_sale' ); 
	}

}

function mos_birdsend_admin_error( string $message ): void {
  $html = '<div class="notice notice-error" style="padding: 7px;"><strong>MOS Birdsend Plugin</strong>: ' . $message . '</div>';
  \add_action( 'admin_notices', function() use ($html) {
    echo $html;
  } );
}

if ( ! class_exists( '\MOS\Requests\Client' ) ) {
	$abort_init = true;
	mos_birdsend_admin_error( 'Class \MOS\Requests\Client not defined.' );
}

if ( ! function_exists( '\MOS\Async\add_action_async' ) ) {
	$abort_init = true;
	mos_birdsend_admin_error( 'Function \MOS\Async\add_action_async not defined.' );
}

if ( $abort_init ) {
	exit;
}

$mos_birdsend_plugin = new MosBirdsendPlugin();
$mos_birdsend_plugin->init();

// /**
//  * #TEST
//  * 
//  * Instructions:
//  * 1. Uncomment this
//  * 2. Go to functions.php > subscribe_to_mos_members()
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
// 			subscribe_to_mos_members( $user_id );
// 		},
//   ] );
// } );