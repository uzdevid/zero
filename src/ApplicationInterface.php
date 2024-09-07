<?php

namespace Zero;

interface ApplicationInterface {
    public function __construct(
        string $path
    );
    public function run(): void;
}