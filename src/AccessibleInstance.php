<?php

namespace e200\MakeAccessible;

use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;

/**
 * AccessibleInstance.
 *
 * Makes encapsulated instance members accessible.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class AccessibleInstance
{
    /** @var object */
    protected $instance;

    /** @var Reflector */
    protected $reflector;

    public function __construct($instance, Reflector $reflector)
    {
        $this->instance = $instance;
        $this->reflector = $reflector;
    }

    /**
     * Gets the `static::$instance`.
     *
     * @return object
     */
    protected function getInstance()
    {
        return $this->instance;
    }

    /**
     * Calls `$propertyName` method in the `static::$instance`.
     *
     * @param string $methodName Method name.
     * @param array  $arguments  Method arguments.
     *
     * @throws MethodNotFoundException
     *
     * @return mixed
     */
    public function __call($methodName, $arguments)
    {
        $refClass = $this->reflector->getReflectedClass();

        if ($refClass->hasMethod($methodName)) {
            $method = $refClass->getMethod($methodName);

            $this->reflector->makeAccessibleIfInaccessible($method);

            return $method->invokeArgs($this->getInstance(), $arguments);
        } else {
            throw new MethodNotFoundException("No method \"{$methodName}\" was found on class \"{$refClass->getName()}\".");
        }
    }

    /**
     * Gets the `$propertyName`.
     *
     * @param string $propertyName Property name.
     *
     * @throws PropertyNotFoundNotFoundException
     *
     * @return mixed
     */
    public function __get($propertyName)
    {
        $refClass = $this->reflector->getReflectedClass();

        if ($refClass->hasProperty($propertyName)) {
            $property = $refClass->getProperty($propertyName);

            $this->reflector->makeAccessibleIfInaccessible($property);

            return $property->getValue($this->getInstance());
        } else {
            throw new PropertyNotFoundNotFoundException("No property \"{$propertyName}\" was found on class \"{$refClass->getName()}\".");
        }
    }

    /**
     * Access `$propertyName` and insert the `$value` into it.
     *
     * @param string $propertyName.
     * @param mixed  $value
     *
     * @throws PropertyNotFoundNotFoundException
     */
    public function __set($propertyName, $value)
    {
        $refClass = $this->reflector->getReflectedClass();

        if ($refClass->hasProperty($propertyName)) {
            $property = $refClass->getProperty($propertyName);

            $this->reflector->makeAccessibleIfInaccessible($property);

            $property->setValue($this->getInstance(), $value);
        } else {
            throw new PropertyNotFoundNotFoundException("No property {$propertyName} was found on class \"{$refClass->getName()}\".");
        }
    }
}
