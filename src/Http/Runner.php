<?php

declare(strict_types=1);

namespace Zero\Http;

use Zero\RunnerInterface;

class Runner implements RunnerInterface {
    /**
     * @param string $rootPath
     */
    public function __construct(
        public readonly string $rootPath
    ) {
    }

    public function run(): void {

    }
}