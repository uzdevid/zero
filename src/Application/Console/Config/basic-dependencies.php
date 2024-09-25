<?php

use Zero\Application\RouterInterface;
use Zero\Console\Application;
use Zero\Console\Router\Router;

return [
    Application::class => [
        'class' => Application::class,
        '__construct()' => [
            'name' => 'Zero Console Application'
        ]
    ],
    RouterInterface::class => [
        'class' => Router::class
    ],
];