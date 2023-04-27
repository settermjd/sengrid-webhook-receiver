<?php

return [
    'sendgrid' => [
        'api_key' => $_SERVER['SENDGRID_API_KEY'],
        'webhook' => [
            'public_key' => $_SERVER['SENDGRID_WEBHOOK_PUBLIC_KEY']
        ]
    ]
];
