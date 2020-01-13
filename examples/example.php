<?php

// Don't miss to install needful libraries using Composer to generate
// autoload.php file.
require __DIR__ . '/../vendor/autoload.php';

use Samaphp\Desktime\Account;
use Samaphp\Desktime\Employee;

print '<PRE>';

$Account = new Account();
// You can find your api_key value here: https://desktime.com/app/api //.
$Account->setCredentials(['api_key' => 'API_KEY_HERE']);
$company = $Account->company();
print_r($company);

$Employee = new Employee();
// You can find your api_key value here: https://desktime.com/app/api //.
$Employee->setCredentials(['api_key' => 'API_KEY_HERE']);

// Getting all employees.
// $all = $Employee->all(['date' => '2019-11-13', 'period' => 'month']); //.
$all_employees = $Employee->all();
print_r($all_employees);

// Getting current employee. (the owner of this API key)
$current_employee = $Employee->get();
print_r($current_employee);

print '</PRE>';
