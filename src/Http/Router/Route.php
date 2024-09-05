<?php

namespace Zero\Http\Router;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
readonly class Route {
    /**
     * @param string $path
     * @param array $methods
     */
    public function __construct(
        public string $path,
        public array $methods
    ) {
    }
}
