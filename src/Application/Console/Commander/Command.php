<?php

declare(strict_types=1);

namespace Zero\Application\Console\Commander;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Command {
    /**
     * @param string $name
     */
    public function __construct(
        public string $name
    ) {
    }
}