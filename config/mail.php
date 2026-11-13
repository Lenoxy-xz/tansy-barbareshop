<?php
return [
    'default' => env('MAIL_MAILER', 'smtp'),
    'mailers' => ['smtp' => ['transport' => 'smtp']],
    'from' => ['address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'), 'name' => env('MAIL_FROM_NAME', 'Tansy Barbershop')],
];
