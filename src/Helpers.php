<?php

namespace Samaphp\Desktime;

/**
 * Class Helpers.
 */
class Helpers extends DesktimeClass {

  /**
   * Constructs a new object.
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Getting last work days.
   */
  public function get_last_work_days($limit = 5, $week_days = [1, 2, 3, 4, 7]) {
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

}
