<?php

namespace Bataille\Etc;

use ReflectionClass;

/**
 * @name \Bataille\Etc\DependencyInjectionContainer
 */
class DependencyInjectionContainer
{
    /**
     * @var array of objects
     */
    private array $instances;

    /**
     * @param string $className
     * @return mixed|object|null
     * @throws \ReflectionException
     */
    public function instanciateClass(string $className)
    {
        if (isset($instances[$className])) {
            return $instances[$className];
        }

        $reflector = new ReflectionClass($className);
        if (!$reflector->isInstantiable()) {
            return null;
        }

        $arguments = $this->getConstructorArguments($reflector);

        if (!$arguments) {
            return $reflector->newInstance(); //This is the recursive loop main exit condition
        }

        $dependencies = $this->injectDependencies($arguments);
        $instances[$className] = $reflector->newInstanceArgs($dependencies);
        return $instances[$className];
    }

    private function getConstructorArguments(ReflectionClass $reflector)
    {
        $constructor = $reflector->getConstructor();
        if(!$constructor) {
            return null;
        }
        return $constructor->getParameters();
    }

    private function injectDependencies($arguments)
    {
        $dependenciesClasses = [];
        foreach ($arguments as $argument) {
            //getName method exist in php8 but not in previous versions. (hence the benefice of using docker)
            $dependency = $argument->getType()->getName();
            //This is recursive, because instanciateClass call injectDependencies, the loop continues until no more dependencies are needed for classes
            $dependenciesClasses[] = $this->instanciateClass($dependency);
        }

        return $dependenciesClasses;
    }
}