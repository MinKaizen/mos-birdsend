<?php

namespace MOS\Birdsend;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

function subscribe_to_mos_members( int $user_id ) {
    $responses = [];
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

    try {
        $responses['create_contact'] = $client->post( BASE_URL_CONTACTS );
    } catch (RequestException $e) {
        $responses['create_contact'] = $e->getResponse();
        if ( is_exception_email_taken( $responses['create_contact'] ) ) {
            $contact_id = get_contact_id( $body['email'] );
            $responses['update_contact'] = $client->patch( BASE_URL_CONTACTS . "/$contact_id" );
            $responses['subscribe_contact'] = $client->post( BASE_URL_CONTACTS . "/$contact_id/subscribe" );
        }
    }

    foreach ( $responses as $event_name => $response ) {
        $log_message = generate_log_message( $event_name, $response );
        log( $log_message );
    }
}

function is_exception_email_taken( $guzzle_response ) {
    if ( !method_exists( $guzzle_response, 'getBody' ) ) {
        return false;
    }

    $body = json_decode( (string) $guzzle_response->getBody() );
    $is_email_taken = !empty( $body->errors->email[0] ) && $body->errors->email[0] == ERROR_MESSAGE_EMAIL_TAKEN;

    return $is_email_taken;
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
    $time_stamp = date('Y-n-j H:i');
    $uploads_dir  = \wp_get_upload_dir();
    $logs_dir = $uploads_dir['basedir'] . '/mos-logs';
    $log_file = $logs_dir . '/birdsend.log';

    if ( ! is_dir( $logs_dir ) ) {
        mkdir( $logs_dir, 0755, true );
    }

    file_put_contents( $log_file, "$time_stamp: $message" . PHP_EOL, \FILE_APPEND );
}

function generate_log_message( string $event_name , $guzzle_response ) {
    if ( !method_exists( $guzzle_response, 'getBody' ) ) {
        return "[LOG_ERROR] Tried to generate a log message for a non-guzzle response";
    }

    $status_code = $guzzle_response->getStatusCode();
    $reason_phrase = $guzzle_response->getReasonPhrase();
    $body = (string) $guzzle_response->getBody();
    $log_message = "[$event_name] [$status_code] ['$reason_phrase'] $body";

    return $log_message;
}