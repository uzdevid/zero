<?php
declare(strict_types=1);

namespace Zero\Di;

use Psr\Container\ContainerInterface;
use ReflectionException;
use RuntimeException;
use function is_array;
use function is_callable;

final class Container implements ContainerInterface {
    private DefinitionStorage $definitionStorage;
    private array $instances = [];

    public function __construct() {
        $this->definitionStorage = new DefinitionStorage([
            ContainerInterface::class => $this
        ]);
    }

    /**
     * @param string $id
     * @param mixed $definition
     * @return void
     */
    public function addDefinition(string $id, mixed $definition): void {
        $this->definitionStorage->set($id, $definition);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ReflectionException
     */
    public function get(string $id): mixed {
        if (!array_key_exists($id, $this->instances)) {
            $this->instances[$id] = $this->build($id);
        }

        return $this->instances[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool {
        return $this->definitionStorage->has($id);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ReflectionException
     */
    public function build(string $id): mixed {
        if ($this->definitionStorage->has($id) === false) {
            if (class_exists($id)) {
                return (new ClassDefinition)->build($this, $id);
            }

            throw new RuntimeException("Service '$id' doesn't exist in storage.");
        }

        $definition = $this->definitionStorage->get($id);

        if (is_callable($definition)) {
            return $definition($this);
        }

        if (is_array($definition) && isset($definition['class'])) {
            return (new ArrayDefinition)->build($this, $definition);
        }

        if (class_exists($definition)) {
            return (new ClassDefinition)->build($this, $definition);
        }

        return $definition;
    }
}
