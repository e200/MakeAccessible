<?php

namespace e200\MakeAccessible;

use ReflectionClass;
use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\InvalidInstanceException;
use e200\MakeAccessible\Exceptions\NonSingletonClassException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;
use e200\MakeAccessible\Exceptions\InvalidSingletonClassNameException;

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

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @param object $instance. The instance that will have
     *                          their members accessible.
     *
     * @throws InvalidInstanceException
     *
     * @return Make
     */
    public static function accessible($instance)
    {
        if (is_object($instance)) {
            $make = new self();
            $make->setInstance($instance);

            $make->setRefClass(
                $make->reflect($instance)
            );

            return $make;
        } else {
            throw new InvalidInstanceException('Invalid instance provided.');
        }
    }

    /**
     * @param string $singletonClass The singleton class name.
     * @param array $arguments       Constructor arguments.
     *
     * @return object
     *
     * @throws InvalidSingletonClassNameException
     */
    public static function instance($singletonClass, $arguments = [])
    {
        if (is_string($singletonClass)) {
            return (new self())->instantiate($singletonClass, $arguments);
        } else {
            throw new InvalidSingletonClassNameException('Invalid singleton class name provided.');
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
     * @param array  $arguments  Method arguments.
     *
     * @throws MethodNotFoundException
     *
     * @return mixed
     */
    public function __call($methodName, $arguments)
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
     * @throws PropertyNotFoundNotFoundException
     *
     * @return mixed
     */
    public function __get($propertyName)
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
    public function __set($propertyName, $value)
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
     * Makes a new ReflectionClass instance.
     *
     * @param $class
     *
     * @return ReflectionClass
     */
    protected function reflect($class)
    {
        return new ReflectionClass($class);
    }

    /**
     * Unlocks an encapsulated property or a method inside `MakeAccessible::$instance`.
     *
     * @param \ReflectionMethod|\ReflectionProperty $refObject
     */
    protected function unlock($refObject)
    {
        if (!$refObject->isPublic()) {
            $refObject->setAccessible(true);
        }
    }

    /**
     * @param string $singletonClass
     * @param array $arguments       Constructor arguments.
     *
     * @return object
     *
     * @throws NonSingletonClassException
     */
    protected function instantiate($singletonClass, $arguments = [])
    {
        // Getting the reflection class that represents the `$singletonClass`.
        $refClass = $this->reflect($singletonClass);

        // Getting the constructor of the `$refClass`.
        $constructor = $refClass->getConstructor();

        /*
         * Every singleton class has a protected or private constructor.
         *
         * If our class constructor is null, that means our class has no
         * constructor, what also means that our class isn't a singleton class.
         *
         * In this case we throw a `NonSingletonClassException`.
         */
        if (!is_null($constructor)) {
            $instance = $refClass->newInstanceWithoutConstructor();

            /*
             * If our class has parameters on constructor, we unlock it
             * (since is protected or private) and put `$arguments` into the
             * constructor.
             */
            if ($constructor->getNumberOfParameters() > 0) {
                $this->unlock($constructor);

                $constructor->invokeArgs($instance, $arguments);
            }

            return $instance;
        } else {
            throw new NonSingletonClassException("Trying to instantiate a non singleton class.");
        }
    }
}
