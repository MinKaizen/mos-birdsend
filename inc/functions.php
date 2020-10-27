<?php

namespace MOS_Birdsend;

use GuzzleHttp\Client;

function subscribe_to_mos_members( int $user_id ) {
    $body = prepare_payload( $user_id, SEQUENCE_MOS_MEMBERS );
    $client = new Client( [
        'base_uri' => BASE_URL_CONTACTS,
        'headers' => [
            'Authorization' => HEADER_AUTH,
            'Accept' => HEADER_ACCEPT,
            'Content-type' => HEADER_CONTENT_TYPE,
        ],
        'json' => $body,
    ] );

    $responses['create_contact'] = $client->post( BASE_URL_CONTACTS );

    $time_stamp = date('Y-n-j H:i');
    foreach ( $responses as $event_name => $response ) {
        $status_code = $response->getStatusCode();
        $reason_phrase = $response->getReasonPhrase();
        $body = (string) $response->getBody();
        $log_message = "$time_stamp: [$event_name] [$status_code] ['$reason_phrase'] $body";
        log( $log_message );
    }
}

function get_contact_id( $email ) {
    $client = new Client( [
        'base_uri' => BASE_URL_CONTACTS,
        'headers' => [
            'Authorization' => HEADER_AUTH,
            'Accept' => HEADER_ACCEPT,
            'Content-type' => HEADER_CONTENT_TYPE,
        ],
        'json' => [
            'search_by' => 'email',
            'keyword' => $email,
            'page' => 1,
            'per_page' => 1,
        ]
    ] );
    
    $response = $client->get( BASE_URL_CONTACTS );
    $body = json_decode( $response->getBody());
    $contact_id = $body->data[0]->contact_id;
    
    return $contact_id;
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

function log( string $message ): void {
    $uploads_dir  = \wp_get_upload_dir();
    $logs_dir = $uploads_dir['basedir'] . '/mos-logs';
    $log_file = $logs_dir . '/birdsend.log';

    if ( ! is_dir( $logs_dir ) ) {
        mkdir( $logs_dir, 0755, true );
    }

    file_put_contents( $log_file, $message . PHP_EOL, \FILE_APPEND );
}