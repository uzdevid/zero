<?php

namespace Zero\Di;

use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;
use Throwable;

class Definition {
    /**
     * @param ContainerInterface $container
     * @param array $parameters
     * @param array $constructParams
     * @return array
     * @throws ReflectionException
     */
    protected function resolveParameters(ContainerInterface $container, array $parameters, array $constructParams = []): array {
        $dependencies = [];

        $haveVariadicParameter = false;

        foreach ($parameters as $parameter) {
            $paramName = $parameter->getName();
            $type = $parameter->getType();

            if (array_key_exists($paramName, $constructParams)) {
                $dependencies[] = $constructParams[$paramName];
            } else if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                if ($parameter->isVariadic()) {
                    $haveVariadicParameter = true;
                } else if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new RuntimeException("Unresolvable dependency [$parameter] in class {$parameter->getDeclaringClass()?->getName()}");
                }
            } else {
                $dependencies[] = $this->getDependenciesRecursive($container, $parameter);
            }
        }

        if ($haveVariadicParameter) {
            return array_merge($dependencies, $constructParams);
        }

        return $dependencies;
    }

    /**
     * @param ContainerInterface $container
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws ReflectionException
     */
    protected function getDependenciesRecursive(ContainerInterface $container, ReflectionParameter $parameter): mixed {
        try {
            return $container->get($parameter->getType());
        } catch (Throwable $e) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            $dependency = $container->build($parameter->getType()?->getName());
            $container->addDefinition($parameter->getType(), $dependency);

            return $dependency;
        }
    }
}