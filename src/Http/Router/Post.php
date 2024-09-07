<?php

declare(strict_types=1);

namespace Zero\Http\Router;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Post extends Route {
    /**
     * @param string $path
     */
    public function __construct(
        public string $path
    ) {
        parent::__construct($this->path, ['POST']);
    }
}