<?php

namespace MOS\Birdsend;

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