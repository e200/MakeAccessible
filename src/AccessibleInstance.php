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
     * Called when trying to invoke an instance method.
     *
     * @param string $methodName
     * @param array  $arguments
     *
     * @return mixed|null
     */
    public function __call($methodName, $arguments)
    {
        return $this->call($methodName, $arguments);
    }

    /**
     * Called when trying to set a property value.
     *
     * @param string $propertyName
     * @param mixed  $value
     */
    public function __set($propertyName, $value)
    {
        $this->set($propertyName, $value);
    }

    /**
     * Called when trying to get a property value.
     *
     * @param string $propertyName
     *
     * @return mixed
     */
    public function __get($propertyName)
    {
        return $this->get($propertyName);
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
    public function call($methodName, $arguments)
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
     * Access `$propertyName` and insert the `$value` into it.
     *
     * @param string $propertyName.
     * @param mixed  $value
     *
     * @throws PropertyNotFoundNotFoundException
     */
    public function set($propertyName, $value)
    {
        if ($this->reflectedClass->hasProperty($propertyName)) {
            $property = $this->reflectedClass->getProperty($propertyName);

            $this->reflector->makeAccessibleIfNot($property);

            $property->setValue($this->getInstance(), $value);
        } else {
            throw new PropertyNotFoundNotFoundException("No property {$propertyName} was found on class \"{$this->reflectedClass->getName()}\".");
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
    public function get($propertyName)
    {
        if ($this->reflectedClass->hasProperty($propertyName)) {
            $property = $this->reflectedClass->getProperty($propertyName);

            $this->reflector->makeAccessibleIfNot($property);

            // Necessary to return the value by reference.
            return $property->getValue($this->getInstance());
        } else {
            throw new PropertyNotFoundNotFoundException("No property \"{$propertyName}\" was found on class \"{$this->reflectedClass->getName()}\".");
        }
    }

    /**
     * Adds an item to an array property.
     *
     * Unfortunatelly we cannot directly set an array index
     * like: `$instance->array[$index] = 'a'`, so, thats the
     * the temporary alternative until we find another.
     *
     * @param string     $propertyName
     * @param string|int $index
     * @param mixed      $value
     */
    public function setArrayPropertyValue($propertyName, $index, $value)
    {
        $array = $this->get($propertyName);

        $array[$index] = $value;

        $this->set($propertyName, $array);
    }
}
