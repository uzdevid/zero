<?php

namespace Zero\Console;

use Zero\ApplicationInterface;

class ConsoleApplication implements ApplicationInterface {
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