<?php

use Zero\Console\Command\RouteCommand;

return [
    'commander' => [
        'commands' => [
            'route:optimize' => [RouteCommand::class, 'optimize']
        ]
    ]
];