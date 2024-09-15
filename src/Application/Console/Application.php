<?php

declare(strict_types=1);

namespace Zero\Console;

class Application {
    /**
     * @param string $name
     */
    public function __construct(
        public readonly string $name
    ) {
    }
}