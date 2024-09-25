<?php

declare(strict_types=1);

namespace Zero\Console;

readonly class Application {
    /**
     * @param string $name
     */
    public function __construct(
        public string $name
    ) {
    }
}