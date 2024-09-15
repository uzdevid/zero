<?php

namespace Zero\Di;

use ReflectionClass;
use ReflectionException;
use RuntimeException;
use function is_null;

class Container {
    private array $instances = [];

    /**
     * @param string $id
     * @param mixed $definition
     * @return void
     */
    public function addDefinition(string $id, mixed $definition): void {
        $this->instances[$id] = $definition;
    }

    /**
     * @param string $id
     * @return mixed|object|null
     * @throws ReflectionException
     */
    public function get(string $id): mixed {
        if (!array_key_exists($id, $this->instances)) {
            throw new RuntimeException(sprintf("Service '%s' not found in the container.", $id));
        }

        $className = $this->instances[$id];

        $reflectionClass = new ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            return new $className;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencyClass = $parameter->getType()?->getName();

            if (!is_null($dependencyClass) && class_exists($dependencyClass)) {
                $dependencies[] = $this->get($dependencyClass);
            } else {
                throw new RuntimeException(sprintf("Cannot resolve dependency '%s' for service '%s'", $parameter->getName(), $id));
            }
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
