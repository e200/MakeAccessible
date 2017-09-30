<?php

namespace e200\MakeAccessible;

use ReflectionClass;
use e200\MakeAccessible\Exceptions\InvalidObjectInstanceException;
use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;

/**
 * Class Make.
 *
 * Makes encapsulated instance members accessible.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class Make
{
    /** @var object */
    protected $instance;

    /** @var \ReflectionClass */
    protected $refClass;

    /*
     * MakeAccessible constructor.
     *
     * @param object $instance. The instance that will be reflected.
     */
    protected function __construct($instance)
    {
        $refClass = new ReflectionClass($instance);

        $this->setInstance($instance);
        $this->setRefClass($refClass);
    }

    protected function __clone() {}

    /**
     * @param object $instance The instance that you'll gain access.
     *
     * @return Make
     *
     * @throws InvalidObjectInstanceException
     */
    static function accessible($instance)
    {
        if (is_object($instance)) {
            return new Make($instance);
        } else {
            throw new InvalidObjectInstanceException("Invalid instance provided.");
        }
    }

    /**
     * Sets the `MakeAccessible::$instance`.
     *
     * @param object $instance
     */
    protected function setInstance($instance)
    {
        $this->instance = $instance;
    }

    /**
     * Gets the `MakeAccessible::$instance`.
     *
     * @return object
     */
    protected function getInstance()
    {
        return $this->instance;
    }

    /**
     * Sets the `MakeAccessible::$refClass`.
     *
     * @param \ReflectionClass $refClass
     */
    protected function setRefClass($refClass)
    {
        $this->refClass = $refClass;
    }

    /**
     * Gets the `MakeAccessible::$refClass`.
     *
     * @return \ReflectionClass
     */
    protected function getRefClass()
    {
        return $this->refClass;
    }

    /**
     * Calls `$propertyName` method in the `MakeAccessible::$instance`.
     *
     * @param string $methodName Method name.
     * @param array $arguments Method arguments.
     *
     * @return mixed
     *
     * @throws MethodNotFoundException
     */
    function __call($methodName, $arguments)
    {
        $refClass = $this->getRefClass();

        if ($refClass->hasMethod($methodName)) {
            $method = $refClass->getMethod($methodName);

            $this->unlock($method);

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
     * @return mixed
     *
     * @throws PropertyNotFoundNotFoundException
     */
    function __get($propertyName)
    {
        $refClass = $this->getRefClass();

        if ($refClass->hasProperty($propertyName)) {
            $property = $refClass->getProperty($propertyName);

            $this->unlock($property);

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
    function __set($propertyName, $value)
    {
        $refClass = $this->getRefClass();

        if ($refClass->hasProperty($propertyName)) {
            $property = $refClass->getProperty($propertyName);

            $this->unlock($property);

            $property->setValue($this->getInstance(), $value);
        } else {
            throw new PropertyNotFoundNotFoundException("No property {$propertyName} was found on class \"{$refClass->getName()}\".");
        }
    }

    /**
     * Unlocks if encapsulated a property or a method inside `MakeAccessible::$instance`.
     *
     * @param \ReflectionMethod|\ReflectionProperty $refObject
     */
    protected function unlock($refObject)
    {
        if (!$refObject->isPublic()) {
            $refObject->setAccessible(true);
        }
    }
}
