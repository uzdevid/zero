<?php
declare(strict_types=1);

namespace Zero\Application;

interface RunnerInterface {
    public function __construct(
        string $rootPath
    );
    public function run(): void;
}