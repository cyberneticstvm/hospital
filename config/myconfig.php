<?php

use Illuminate\Support\Facades\Facade;

return [
    'sms' => [
        'api_id' => 'APIi153VPhF88384',
        'api_password' => env('SMS_PASSWORD', ''),
        'sms_type' => 'Transactional',
        'sms_encoding' => '1',
        'sender' => 'DEVIHL',
        'template_id' => '126781',
    ],
    'sms1' => [
        'api_id' => 'APIi153VPhF88384',
        'api_password' => env('SMS_PASSWORD', ''),
        'sms_type' => 'Transactional',
        'sms_encoding' => '1',
        'sender' => 'DEVIHL',
        'template_id' => '128391',
    ],
    'sms2' => [
        'api_id' => 'APIi153VPhF88384',
        'api_password' => env('SMS_PASSWORD', ''),
        'sms_type' => 'Text',
        'sms_encoding' => '1',
        'sender' => 'DEVIHL',
        'template_id' => '145345',
    ],
    'whatsapp' => [
        'token' => 'EAAIEBU1jXJ8BO6Q83tlPNT5TmhoabjogW9VK8vqZCeQNrgjCvn3Ka5ytqLvMyWY0oEpOIuFtuTy9RMhnllSyvZB7ZC23LtZCnUAuZB2TyxCiS8bZBNwZBNqWt2wIFmqKTFWPWcLKTAfZCmmy6DeZBadEMgx12eHjLUGhinLabTPBvMZB1gCo8foNmCVvUR2RrfNB9EJQZDZD',
        'token_vijo' => 'EAAN7uMpo4zIBO7t221eWEFqKGGmKg9uZAnDi7yVEUwqFPs0J7QeJOZC9lUtZC9bIErQJvi8KByMkZB9O6OXhwdKtW1L5S32AZCZAZBcheoO7mlO9DR8ospMlFrqooif2YoFYIdifUFD0NlWwKUpNbaXEACU5PYG4pg7SRFIZAkz5NZBDmPbyBxZBLv4NvQEdq0eZCXfVwZDZD',
    ],

    'domain' => [
        'subdomain' => env('SUBDOMAIN'),
    ]
];
