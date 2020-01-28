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
   * List all employees.
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
    $res = $this->makeGetCall($url);
    return $res;
  }

  /**
   * Get specific employee or current employee.
   */
  public function get($options = []) {
    $query = [];
    // Add optional parameters if provided.
    if (isset($options['id'])) {
      $query['id'] = $options['id'];
    }
    if (isset($options['date'])) {
      $query['date'] = $options['date'];
    }

    $url = 'employee';
    $url = $this->buildUrl($url, $query);
    $res = $this->makeGetCall($url);
    return $res;
  }

  /**
   * Get specific employee data by his Desktime email.
   */
  public function getEmployeeByEmail($email) {
    $result = new \stdClass();
    $result->pass = FALSE;

    $all_employees = $this->all();
    if (($all_employees->pass) && isset($all_employees->body->employees)) {
      $employees = reset($all_employees->body->employees);
      if (count($employees) > 0) {
        foreach ($employees as $employee) {
          if ($email == $employee->email) {
            $result->pass = TRUE;
            $result->data = $employee;
            break;
          }
        }
      }
    }
    return $result;
  }
}
