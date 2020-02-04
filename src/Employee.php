<?php

namespace Samaphp\Desktime;

/**
 * Class Employee.
 */
class Employee extends DesktimeClass {

  private $employee_id;

  /**
   * Constructs a new object.
   */
  public function __construct($employee_id = FALSE) {
    parent::__construct();
    $this->employee_id = $employee_id;
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
    // We will override the employee ID if it was injected in this class.
    if (isset($this->employee_id) && is_numeric($this->employee_id)) {
      $query['id'] = $this->employee_id;
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
