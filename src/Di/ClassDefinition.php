<?php

namespace Zero\Di;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class ClassDefinition extends Definition {
    /**
     * @param ContainerInterface $container
     * @param string $definition
     * @return object
     * @throws ReflectionException
     */
    public function build(ContainerInterface $container, string $definition): object {
        $reflectionClass = new ReflectionClass($definition);

        if (!$reflectionClass->isInstantiable()) {
            throw new RuntimeException("Service `$definition` is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            return new $definition;
        }

        $dependencies = $this->resolveParameters($container, $constructor->getParameters());

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}