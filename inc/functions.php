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

    $request_args = [
        'json' => [
            'event' => 'Register New User',
            'time' => date('g:i:s'),
            'user_id' => $user_id,
        ],
    ];

    $response = $client->get( $url, $request_args );

    return $response;
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