<?php
declare(strict_types=1);

namespace Zero\Di;

use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use RuntimeException;
use function is_null;

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
    private function build(string $id): mixed {
        if ($this->definitionStorage->has($id)) {
            $definition = $this->definitionStorage->get($id);
        } elseif (class_exists($id)) {
            $definition = $id;
        } else {
            throw new RuntimeException("Service '$id' doesn't exist in storage.");
        }

        if (is_callable($definition)) {
            return $definition($this);
        }

        $reflectionClass = new ReflectionClass($definition);

        if (!$reflectionClass->isInstantiable()) {
            throw new RuntimeException("Service `$id` is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            return new $id;
        }

        $parameters = $constructor->getParameters();

        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else if ($parameter->isVariadic()) {
                    $dependencies[] = [];
                } else {
                    throw new RuntimeException("Unresolvable dependency [$parameter] in class {$parameter->getDeclaringClass()?->getName()}");
                }
            }

            $name = $type->getName();

            try {
                $dependencies[] = $this->get($name);
            } catch (Exception $e) {
                if ($parameter->isOptional()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    $dependency = $this->build($parameter->getType()?->getName());
                    $this->addDefinition($name, $dependency);
                    $dependencies[] = $dependency;
                }
            }
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
