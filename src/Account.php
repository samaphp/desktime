<?php

namespace Samaphp\Desktime;

use Samaphp\Desktime\DesktimeClass;

/**
 * Class Account.
 */
class Account extends DesktimeClass {

  /**
   * Constructs a new object.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * List all boxes in pipeline.
   */
  public function company() {
    $url = 'company';
    $url = $this->buildUrl($url);
    $res = $this->get($url, '');
    return $res;
  }

}
