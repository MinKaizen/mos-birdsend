<?php

namespace MOS_Birdsend;

use GuzzleHttp\Client;

function log_gform_activate_user( $user_id ) {
    $url = 'https://fa9438717d678d74b5cb20e6e5b41923.m.pipedream.net';
    $client = new Client([
        'base_uri' => $url,
        'timeout'  => 2.0,
        'headers' => [
            'Authorization' => HEADER_AUTH,
            'Accept' => HEADER_ACCEPT,
            'Content-type' => HEADER_CONTENT_TYPE,
        ],
        
    ]);

    $body = prepare_payload( $user_id, SEQUENCE_MOS_MEMBERS );
    $request_args = [
        'json' => $body,
    ];

    $response = $client->get( $url, $request_args );
    log_response( $response );

    return $response;
}

function subscribe_and_update( int $user_id, int $sequence_id ) {
    $client = new Client( [
        'base_uri' => BASE_URL_CONTACTS,
        'headers' => [
            'Authorization' => HEADER_AUTH,
            'Accept' => HEADER_ACCEPT,
            'Content-type' => HEADER_CONTENT_TYPE,
        ],
        'json' => prepare_payload( $user_id, $sequence_id ),
    ] );

    $responses['create_contact'] = $client->post( BASE_URL_CONTACTS );
    
    foreach ( $responses as $response ) {
        log_response( $response );
    }
}

function prepare_payload( int $user_id, int $sequence_id ): array {
    $user = \get_user_by( 'id', $user_id );

    if ( !( $user instanceof \WP_User ) ) {
        return [];
    }

    $data = [
        'email' => $user->user_email,
        'sequence_id' => $sequence_id,
        'fields' => [
            'first_name' => $user->get('first_name'),
            'username' => $user->get('user_login'),
            'ip_address' => $user->get('ip'),
        ],
    ];

    return $data;
}

function log_response( $response ): void {
    if ( is_array( $response ) ) {
        $message = print_r( $response, true );
    } elseif ( is_string( $response ) ) {
        $message = $response;
    }

    $uploads_dir  = \wp_get_upload_dir();
    $logs_dir = $uploads_dir['basedir'] . '/mos-logs';
    $log_file = $logs_dir . '/birdsend.log';

    if ( ! is_dir( $logs_dir ) ) {
        mkdir( $logs_dir, 0755, true );
    }

    file_put_contents( $log_file . PHP_EOL, $message );
}