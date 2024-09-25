<?php
declare(strict_types=1);

namespace Zero\Di;

use RuntimeException;

final class DefinitionStorage {
    /**
     * @param array $definitions
     */
    public function __construct(
        private array $definitions = []
    ) {
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool {
        return isset($this->definitions[$id]);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get(string $id): mixed {
        if (!$this->has($id)) {
            throw new RuntimeException("Service '$id' doesn't exist in storage.");
        }

        return $this->definitions[$id];
    }

    /**
     * @param string $id
     * @param mixed $definition
     * @return void
     */
    public function set(string $id, mixed $definition): void {
        $this->definitions[$id] = $definition;
    }
}