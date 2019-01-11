<?php

define('APP_ROOT', __DIR__);

return [
    'doctrine' => [
        'meta' => [
            'entity_path' => [
                'app/src/Entity'
            ],
            'auto_generate_proxies' => true,
            'proxy_dir' =>  __DIR__.'/../cache/proxies',
            'cache' => null,
        ],
        'connection' => [
            'driver'   => 'pdo_mysql',
            'host'     => 'localhost',
            'dbname'   => 'slim_oauth2',
            'user'     => 'root',
            'password' => '',
        ]
    ]
];