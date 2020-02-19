<?php

namespace Samaphp\Desktime;

/**
 * Class Helpers.
 */
class Helpers extends DesktimeClass {

  /**
   * The employee.
   *
   * @var \Samaphp\Desktime\Employee
   */
  private $employee;

  /**
   * The desktime and productive goals.
   *
   * @var array
   */
  public $goals = [
    'desktime' => [
      'target' => (60 * 60 * 7.5),
      'minimum' => (60 * 60 * 7),
    ],
    'productive' => [
      'target' => (60 * 60 * 6),
      'minimum' => (60 * 60 * 5),
    ],
  ];

  /**
   * Constructs a new object.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Set the targeted Employee object.
   */
  public function setEmployee($employee) {
    $this->employee = $employee;
    return $this;
  }

  /**
   * Getting last work days.
   */
  public function getLastDays($limit = 5, $week_days = [1, 2, 3, 4, 7]) {
    // Source: https://stackoverflow.com/a/47750178/366884
    $date = date('Y/m/d');
    $ts = strtotime($date);
    // Limit + weekends.
    $offset = $limit + ($limit / 2.5) - 1;
    $ts = $ts - $offset * 86400;
    $last_work_days = [];
    for ($i = 0; $i < 9 + $offset; $i++, $ts += 86400){
      if ($ts < time()) {
        if(in_array(date('N', $ts), $week_days)){ // day code is less then weekday 6 & 7
          $last_work_days[] = date('Y-m-d', $ts);
        }
      }
    }
    return $last_work_days;
  }

  /**
   * Prepare a report as PHP array.
   */
  public function report($days, $type = 'productive') {
    $result = new \stdClass();
    $result->pass = FALSE;
    $result->data = [];
    // @TODO: Validate $days as an array of dates.
    switch ($type) {
      case 'productive':
        $result->pass = TRUE;
        $result->data = self::reportProductive($days);
        break;
    }

    return $result;
  }

  private function reportProductive($days) {
    $report = [];
    $report['days'] = [];
    $report['short'] = [
      'desktime' => [
        'seconds' => 0,
        'hours' => 0,
      ],
      'productive' => [
        'seconds' => 0,
        'hours' => 0,
      ],
    ];
    foreach ($days as $date) {
      $employee = $this->employee->get(['date' => $date]);
      // print_r($employee);exit;
      $date_time = strtotime($date);

      $target_productive_goal = 0;
      if ($employee->body->productiveTime > $this->goals['productive']['target']) {
        $target_productive_goal = 1;
      }

      $minimum_productive_goal = 0;
      if ($employee->body->productiveTime > $this->goals['productive']['minimum']) {
        $minimum_productive_goal = 1;
      }

      $short_productive = 0;
      if ($employee->body->productiveTime < $this->goals['productive']['minimum']) {
        $short_productive = $this->goals['productive']['minimum'] - $employee->body->productiveTime;
        $report['short']['productive']['seconds'] = $short_productive + $report['short']['productive']['seconds'];
        $report['short']['productive']['hours'] = gmdate('H:i:s', $report['short']['productive']['seconds']);
      }

      $target_desktime_goal = 0;
      if ($employee->body->desktimeTime > $this->goals['desktime']['target']) {
        $target_desktime_goal = 1;
      }

      $minimum_desktime_goal = 0;
      if ($employee->body->desktimeTime > $this->goals['desktime']['minimum']) {
        $minimum_desktime_goal = 1;
      }

      $short_desktime = 0;
      if ($employee->body->desktimeTime < $this->goals['desktime']['minimum']) {
        $short_desktime = $this->goals['desktime']['minimum'] - $employee->body->desktimeTime;
        $report['short']['desktime']['seconds'] = $short_desktime + $report['short']['desktime']['seconds'];
        $report['short']['desktime']['hours'] = gmdate('H:i:s', $report['short']['desktime']['seconds']);
      }

      $report['days'][] = [
        'day' => date('l', $date_time),
        'date' => date('Y/m/d', $date_time),
        'productive' => [
          'time' => gmdate('H:i:s', $employee->body->productiveTime),
          'target' => $target_productive_goal,
          'minimum' => $minimum_productive_goal,
          'short' => [
            'seconds' => $short_productive,
            'hours' => gmdate('H:i:s', $short_productive),
          ],
        ],
        'desktime' => [
          'time' => gmdate('H:i:s', $employee->body->desktimeTime),
          'target' => $target_desktime_goal,
          'minimum' => $minimum_desktime_goal,
          'short' => [
            'seconds' => $short_desktime,
            'hours' => gmdate('H:i:s', $short_desktime),
          ],
        ],
      ];
    }

    return $report;
  }

}
