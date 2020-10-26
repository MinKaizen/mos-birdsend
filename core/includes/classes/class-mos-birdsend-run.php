<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This class is used to bring your plugin to life. 
 * All the other registered classed bring features which are
 * controlled and managed by this class.
 * 
 * Within the add_hooks() function, you can register all of 
 * your WordPress related actions and filters as followed:
 * 
 * add_action( 'my_action_hook_to_call', array( $this, 'the_action_hook_callback', 10, 1 ) );
 * or
 * add_filter( 'my_filter_hook_to_call', array( $this, 'the_filter_hook_callback', 10, 1 ) );
 * or
 * add_shortcode( 'my_shortcode_tag', array( $this, 'the_shortcode_callback', 10 ) );
 * 
 * Once added, you can create the callback function, within this class, as followed: 
 * 
 * public function the_action_hook_callback( $some_variable ){}
 * or
 * public function the_filter_hook_callback( $some_variable ){}
 * or
 * public function the_shortcode_callback( $attributes = array(), $content = '' ){}
 * 
 * 
 * HELPER COMMENT END
 */

/**
 * Class Mos_Birdsend_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		MOSBIRDSEND
 * @subpackage	Classes/Mos_Birdsend_Run
 * @author		Martin Cao
 * @since		1.0.0
 */
class Mos_Birdsend_Run{

	/**
	 * Our Mos_Birdsend_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'admin_menu', array( $this, 'register_custom_admin_menu_pages' ), 20 );
		add_action( 'rest_api_init', array( $this, 'add_rest_api_endpoints' ), 20 );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Add custom menu pages
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function register_custom_admin_menu_pages(){

		add_submenu_page( 'options-general.php', 'Birdsend API', 'Birdsend API Settings', MOSBIRDSEND()->settings->get_capability( 'default' ), 'birdsend-api-settings', array( $this, 'custom_admin_menu_page_callback' ), 5 );

	}

	/**
	 * Add custom menu page content for the following
	 * menu item: birdsend-api-settings
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function custom_admin_menu_page_callback(){

		?>
		<p>This is the content area of your custom menu page</p>
		<?php

	}

	/**
	 * Add the REST API endpoints for this plugin
	 *
	 * Accessibility:
	 * https://domain.com/wp-json/mosbirdsend/v1/demo/4
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function add_rest_api_endpoints() {

		if( ! class_exists( 'WP_REST_Server' ) ){
			return;
		}

		register_rest_route( 'mosbirdsend/v1', '/demo/(?P<id>\d+)', array(
			array(
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'prepare_rest_api_demo_response' ),
				'permission_callback' => function( $request ) {
					return true; //Change to limit access
				},
				'args' => array(
					'id' => array(
						'validate_callback' => function($param, $request, $key) {
							return is_numeric( $param );
						}
					),
				),
			),
		) );

	}

	/**
	 * The callback for the demo REST API endpoint
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	object|WP_REST_Request $request Full data about the request.
	 *
	 * @return	object|WP_REST_Response
	 */
	public function prepare_rest_api_demo_response( $request ){
		$response = array(
			'success' => false,
			'msg' => '',
		);

		$id = $request->get_param( 'id' );

		if( is_numeric( $id ) ){
			$response['success'] = true;
			$response['msg'] = __( 'The response was successful. The number you added:', 'mos-birdsend' ) . ' ' . intval( $id );
			return new WP_REST_Response( $response, 200 );
		}

		$response['msg'] = __( 'The given id is not a number.', 'mos-birdsend' );
		return new WP_REST_Response( $response, 500 );
	}

}
