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
    ]        
];

?>