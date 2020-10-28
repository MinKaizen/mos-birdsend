<?php

namespace MOS\Birdsend\Activate;

define( 'NOTICE_ERROR', 0 );
define( 'NOTICE_SUCCESS', 1 );
define( 'DO_ACTION_ASYNC_CALLBACK', '\MOS\Async\add_action_async' );
define( 'REQUESTS_CLIENT_CLASS', '\MOS\Requests\Client' );

function _on_activate() {
  if ( !function_exists( DO_ACTION_ASYNC_CALLBACK ) ) {
    abort_activation( 'Could not find function definition for ' . DO_ACTION_ASYNC_CALLBACK );
  }

  if ( !class_exists( REQUESTS_CLIENT_CLASS ) ) {
    abort_activation( 'Could not find class definition for ' . REQUESTS_CLIENT_CLASS );
  }

  if ( version_compare( PHP_VERSION, MIN_PHP_VER, '<' ) ) {
    abort_activation( "Minimum PHP version not met: " . MIN_PHP_VER );
  }
}

function abort_activation( string $message ): void {
  admin_notice_error( "Plugin activation failed: $message" );
  die( $message );
}

function admin_notice_error( string $message ): void {
  $html = '<div class="notice notice-error is-dismissible">' . $message . '</div>';
  \add_action( 'admin_notices', function() use ($html) {
    echo $html;
  } );
}