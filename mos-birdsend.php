<?php
/**
 * mos-birdsend
 *
 * @package       MOSBIRDSEND
 * @author        Martin Cao
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   mos-birdsend
 * Plugin URI:    https://myonlinestartup.com
 * Description:   API client for Birdsend
 * Version:       1.0.0
 * Author:        Martin Cao
 * Author URI:    https://myonlinestartup.com
 * Text Domain:   mos-birdsend
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with mos-birdsend. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function MOSBIRDSEND() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'MOSBIRDSEND_NAME',			'mos-birdsend' );

// Plugin version
define( 'MOSBIRDSEND_VERSION',		'1.0.0' );

// Plugin Root File
define( 'MOSBIRDSEND_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'MOSBIRDSEND_PLUGIN_BASE',	plugin_basename( MOSBIRDSEND_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'MOSBIRDSEND_PLUGIN_DIR',	plugin_dir_path( MOSBIRDSEND_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'MOSBIRDSEND_PLUGIN_URL',	plugin_dir_url( MOSBIRDSEND_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once MOSBIRDSEND_PLUGIN_DIR . 'core/class-mos-birdsend.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Martin Cao
 * @since   1.0.0
 * @return  object|Mos_Birdsend
 */
function MOSBIRDSEND() {
	return Mos_Birdsend::instance();
}

MOSBIRDSEND();
