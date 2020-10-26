<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Mos_Birdsend_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Mos_Birdsend' ) ) :

	/**
	 * Main Mos_Birdsend Class.
	 *
	 * @package		MOSBIRDSEND
	 * @subpackage	Classes/Mos_Birdsend
	 * @since		1.0.0
	 * @author		Martin Cao
	 */
	final class Mos_Birdsend {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Mos_Birdsend
		 */
		private static $instance;

		/**
		 * MOSBIRDSEND helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Mos_Birdsend_Helpers
		 */
		public $helpers;

		/**
		 * MOSBIRDSEND settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Mos_Birdsend_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'mos-birdsend' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'mos-birdsend' ), '1.0.0' );
		}

		/**
		 * Main Mos_Birdsend Instance.
		 *
		 * Insures that only one instance of Mos_Birdsend exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Mos_Birdsend	The one true Mos_Birdsend
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Mos_Birdsend ) ) {
				self::$instance					= new Mos_Birdsend;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Mos_Birdsend_Helpers();
				self::$instance->settings		= new Mos_Birdsend_Settings();

				//Fire the plugin logic
				new Mos_Birdsend_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'MOSBIRDSEND/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once MOSBIRDSEND_PLUGIN_DIR . 'core/includes/classes/class-mos-birdsend-helpers.php';
			require_once MOSBIRDSEND_PLUGIN_DIR . 'core/includes/classes/class-mos-birdsend-settings.php';

			require_once MOSBIRDSEND_PLUGIN_DIR . 'core/includes/classes/class-mos-birdsend-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'mos-birdsend', FALSE, dirname( plugin_basename( MOSBIRDSEND_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.