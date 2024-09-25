<?php

declare(strict_types=1);

namespace Zero\Console\Command;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use RegexIterator;
use Zero\Console\Application;
use Zero\Console\Router\Command;
use Zero\Http\Router\Route;

class RouteCommand {
    /**
     * @throws ReflectionException
     */
    #[Command('route:optimize')]
    public function optimize(Application $application): void {
        $routes = [];

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($application->rootPath, FilesystemIterator::SKIP_DOTS));

        $controllerFiles = new RegexIterator($files, '/Controller\.php$/');

        foreach ($controllerFiles as $file) {
            require_once $file->getRealPath();

            $classes = get_declared_classes();
            $controllerClass = end($classes);

            $reflector = new ReflectionClass($controllerClass);
            foreach ($reflector->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

                foreach ($attributes as $attribute) {
                    /** @var Route $route */
                    $route = $attribute->newInstance();
                    foreach ($route->methods as $methodName) {
                        $routes[$methodName][$route->path] = [$controllerClass, $method->getName()];
                    }

                }
            }
        }

        file_put_contents($this->cacheFile, serialize($routes));
    }
}