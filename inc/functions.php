<?php

namespace MOS_Birdsend;

use GuzzleHttp\Client;

function subscribe_to_mos_members( int $user_id ) {
    $client = new Client( [
        'base_uri' => BASE_URL_CONTACTS,
        'headers' => [
            'Authorization' => HEADER_AUTH,
            'Accept' => HEADER_ACCEPT,
            'Content-type' => HEADER_CONTENT_TYPE,
        ],
        'json' => prepare_payload( $user_id, SEQUENCE_MOS_MEMBERS ),
    ] );

    $responses['create_contact'] = $client->post( BASE_URL_CONTACTS );
    
    foreach ( $responses as $response ) {
        log_response( $response->getBody() );
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