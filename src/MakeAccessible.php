<?php

namespace e200\MakeAccessible;

use ReflectionClass;

class MakeAccessible
{
    protected $instance;
    protected $refClass;

    function __construct($instance)
    {
        if (is_object($instance)) {
            $this->instance = $instance;

            $this->refClass = new ReflectionClass($instance);
        } else {
            throw new InvalidObjectIntanceException("Invalid object instance provided.");
        }
    }

    /**
     * Calls `$name` method in the `MakeAccessible::$instance`.
     *
     * @param string $name     Method name.
     * @param array $arguments Method arguments.
     *
     * @return mixed
     */
    function __call($name, $arguments)
    {
        if ($this->reflectedClass->hasMethod($name)) {
            $method = $this->reflectedClass->getMethod($name);

            $this->unlock($method);

            return $method->invokeArgs($this->instance, $arguments);
        } else {
            throw new MethodNotFoundException("No method {$name} was found in the provided instance.");
        }
    }

    /**
     * Access `$name` property in the `MakeAccessible::$instance`.
     *
     * @param string $name Property name.
     *
     * @return mixed
     */
    function __get($name)
    {
        if ($this->reflectedClass->hasProperty($name)) {
            $property = $this->reflectedClass->getProperty($name);

            $this->unlock($property);

            return $property->getValue($this->instance);
        } else {
            throw new PropertyNotFoundNotFoundException("No property {$name} was found in the provided instance.");
        }
    }

    /**
     * Unlocks if encapsuled a property or a method inside `MakeAccessible::$instance`.
     *
     * @param \ReflectionMethod|\ReflectionProperty $refObject
     *
     * @return mixed
     */
    function unlock($refObject)
    {
        if ($refObject->isProtected() || $refObject->isPrivate()) {
            $refObject->setAccessible(true);
        }
    }
}
