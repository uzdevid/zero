<?php
declare(strict_types=1);

use Zero\Application\Console\Command\RouteCommand;

return [
    'commander' => [
        'commands' => [
            'route:optimize' => [RouteCommand::class, 'optimize']
        ]
    ]
];