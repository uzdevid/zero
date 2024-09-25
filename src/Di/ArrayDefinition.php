<?php

namespace Zero\Di;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class ArrayDefinition extends Definition {
    /**
     * @param ContainerInterface $container
     * @param array $definition
     * @return object
     * @throws ReflectionException
     */
    public function build(ContainerInterface $container, array $definition): object {
        $class = $definition['class'];
        $constructParams = $definition['__construct()'] ?? [];

        $reflectionClass = new ReflectionClass($class);

        if (!$reflectionClass->isInstantiable()) {
            throw new RuntimeException("Service `$class` is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        $dependencies = $this->resolveParameters($container, $constructor->getParameters(), $constructParams);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}