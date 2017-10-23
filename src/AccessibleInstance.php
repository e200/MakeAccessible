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

    /** @var ReflectionClass */
    protected $reflectedClass;

    public function __construct($instance, Reflector $reflector)
    {
        $this->instance = $instance;
        $this->reflector = $reflector;
        $this->reflectedClass = $reflector->reflect($instance);
    }

    /**
     * Gets the `static::$instance`.
     *
     * @return object
     */
    public function getInstance()
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
        if ($this->reflectedClass->hasMethod($methodName)) {
            $method = $this->reflectedClass->getMethod($methodName);

            $this->reflector->makeAccessibleIfNot($method);

            return $method->invokeArgs($this->getInstance(), $arguments);
        } else {
            throw new MethodNotFoundException("No method \"{$methodName}\" was found on class \"{$this->reflectedClass->getName()}\".");
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
        if ($this->reflectedClass->hasProperty($propertyName)) {
            $property = $this->reflectedClass->getProperty($propertyName);

            $this->reflector->makeAccessibleIfNot($property);

            return $property->getValue($this->getInstance());
        } else {
            throw new PropertyNotFoundNotFoundException("No property \"{$propertyName}\" was found on class \"{$this->reflectedClass->getName()}\".");
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
        if ($this->reflectedClass->hasProperty($propertyName)) {
            $property = $this->reflectedClass->getProperty($propertyName);

            $this->reflector->makeAccessibleIfNot($property);

            $property->setValue($this->getInstance(), $value);
        } else {
            throw new PropertyNotFoundNotFoundException("No property {$propertyName} was found on class \"{$this->reflectedClass->getName()}\".");
        }
    }
}
