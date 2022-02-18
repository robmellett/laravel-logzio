<?php

return [
    'logzio' => [
        'driver' => 'logzio',
        'name'   => 'channel-name',
        'token'  => env('LOGZIO_TOKEN'),
        'type'   => 'http-bulk',
        'ssl'    => true,
        'level'  => 'info',
        'bubble' => true,
        'region' => 'au', // leave empty for default region
    ],
];
