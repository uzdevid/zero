<?php

namespace Zero\Config;

interface ConfigInterface {
    public function get(string $name): mixed;

    public function has(string $name): bool;
}