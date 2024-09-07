<?php

declare(strict_types=1);

namespace Zero\Console;

use Zero\RunnerInterface;

readonly class Runner implements RunnerInterface {
    /**
     * @param string $rootPath
     */
    public function __construct(
        public string $rootPath
    ) {
    }

    public function run(): void {

    }
}