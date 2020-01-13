# Desktime (WIP)
PHP library for desktime.com API.

You need to get your API key value. You can find your api_key value here: [https://desktime.com/app/api](https://desktime.com/app/api)

[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](http://makeapullrequest.com)
[![saythanks](https://img.shields.io/badge/say-thanks-ff69b4.svg)](http://samaphp.com/contact-me)


**Examples:**
```php
$Account = new Account();
$Account->setCredentials(['api_key' => 'API_KEY_HERE']);
$company = $Account->company();
print_r($company);

stdClass Object
(
    [pass] => 1
    [code] => 200
    [body] => stdClass Object
        (
            [name] => Company name
            [work_starts] => 07:15:00
            [work_ends] => 18:00:00
            [work_duration] => 28800
            [working_days] => 79
            [work_start_tracking] => 07:00:00
            [work_stop_tracking] => 22:45:00
            [timezone_identifier] => Asia/Riyadh
            [__request_time] => 1578933311
        )

)
```

You can get all employees or api_key employee owner.
```php
$Employee = new Employee();
$Employee->setCredentials(['api_key' => 'API_KEY_HERE']);

// Getting all employees.
// $all = $Employee->all(['date' => '2019-11-13', 'period' => 'month']); //.
$all_employees = $Employee->all();
print_r($all_employees);

// Getting current employee. (the owner of this API key)
$current_employee = $Employee->get();
print_r($current_employee);
```
