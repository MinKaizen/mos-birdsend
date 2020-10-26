<?php

namespace MOS_Birdsend\Routes;

function test_route() {
  \register_rest_route( 'mos/v1', 'test', [
    'methods' => 'GET',
    'callback' => 'MOS_Birdsend\Handlers\test_method',
  ]);
}