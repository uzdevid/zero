<?php
declare(strict_types=1);

use Zero\Console\Command\RouteCommand;

return [
    'commander' => [
        'commands' => [
            'route:optimize' => [RouteCommand::class, 'optimize']
        ]
    ]
];