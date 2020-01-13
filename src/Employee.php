<?php

namespace Samaphp\Desktime;

/**
 * Class Employee.
 */
class Employee extends DesktimeClass {

  /**
   * Constructs a new object.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * List all boxes in pipeline.
   */
  public function all($options = []) {
    $query = [];
    // Add optional parameters if provided.
    if (isset($options['date'])) {
      $query['date'] = $options['date'];
    }
    if (isset($options['period'])) {
      $query['period'] = $options['period'];
    }

    $url = 'employees';
    $url = $this->buildUrl($url, $query);
    $res = $this->get($url);
    return $res;
  }

}
