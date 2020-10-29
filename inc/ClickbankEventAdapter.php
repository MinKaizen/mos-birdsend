<?php

namespace MOS\Birdsend;

class ClickbankEventAdapter {

  private $body;

  public function __construct( $body ) {
    $this->body = $body;
  }

  public function item_id(): int {
    $item_id = @$this->body->lineItems[0]->itemNo;
    $item_id = $item_id ? $item_id : CBID_NONE;
    return $item_id;
  }

  public function username(): string {
    $username = @$this->body->vendorVariables->mos_username;
    $username = $username ? $username : '';
    return $username;
  }

  public function name(): string {
    $name = @$this->body->vendorVariables->mos_name;
    $name = $name ? $name : '';
    return $name;
  }

  public function email(): string {
    $email = @$this->body->vendorVariables->mos_email;
    $email = $email ? $email : '';
    return $email;
  }


}