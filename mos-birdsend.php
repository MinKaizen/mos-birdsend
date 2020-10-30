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

// Plugin constants
define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );
define( NS . 'PLUGIN_NAME', 'mos-birdsend' );
define( NS . 'PLUGIN_VERSION', '1.0.0' );
define( NS . 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( NS . 'PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( NS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Check mu-plugin dependencies
require( PLUGIN_DIR . '/inc/Activator.php' );
if ( ! Activator::ok_to_init() ) {
	return;
}

// Load plugin
require( PLUGIN_DIR . '/inc/MosBirdsendPlugin.php' );
$mos_birdsend_plugin = new MosBirdsendPlugin();
$mos_birdsend_plugin->init();