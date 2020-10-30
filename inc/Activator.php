<?php

namespace MOS\Birdsend;

class Activator {
  const FUNCTION_DEPENDENCIES = [
    '\MOS\Async\add_action_async',
  ];

  const CLASS_DEPENDENCIES = [
    '\MOS\Requests\Client',
  ];

  public static function ok_to_init() {
    $error_messages = [];
    $ok_to_init = true;

    foreach ( self::FUNCTION_DEPENDENCIES as $function ) {
      if ( ! function_exists( $function ) ) {
        $error_messages[] = "Function $function not defined.";
        $ok_to_init = false;
      }
    }

    foreach ( self::CLASS_DEPENDENCIES as $class ) {
      if ( ! class_exists( $class ) ) {
        $error_messages[] = "Class $class not defined.";
        $ok_to_init = false;
      }
    }

    if ( ! empty( $error_messages ) ) {
      foreach ( $error_messages as $message ) {
        self::admin_notice_error( $message );
      }
    }

    return $ok_to_init;
  }

  private static function admin_notice_error( $message ) {
    $message = '<strong>'.PLUGIN_NAME.'</strong>: ' . $message;
    $html = '<div class="notice notice-error"><p>' . $message .'</p></div>';
    \add_action( 'admin_notices', function() use($html) {
      echo $html;
    } );
  }
  
}