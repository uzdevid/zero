<?php
declare(strict_types=1);

namespace Zero;

interface RunnerInterface {
    public function __construct(
        string $path
    );
    public function run(): void;
}