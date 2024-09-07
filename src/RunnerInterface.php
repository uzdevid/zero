<?php

namespace Zero;

interface RunnerInterface {
    public function __construct(
        string $path
    );
    public function run(): void;
}