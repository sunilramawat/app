<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'Paypal' => $baseDir . '/plugins/Paypal/',
        'Twilio12' => $baseDir . '/plugins/Twilio12/'
    ]
];
