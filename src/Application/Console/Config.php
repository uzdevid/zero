<?php

namespace Zero\Application\Console;

use Zero\Config\ConfigInterface;

class Config implements ConfigInterface {
    public function __construct(
        private array $params
    ) {
    }

    public function get(string $name): array {
        return $this->params[$name];
    }

    public function has(string $name): bool {

    }
}