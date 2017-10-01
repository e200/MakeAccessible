<?php

namespace e200\MakeAccessible;

use ReflectionClass;
use e200\MakeAccessible\Exceptions\InvalidInstanceException;
use e200\MakeAccessible\Exceptions\InvalidSingletonClassNameException;

/**
 * Class Make.
 *
 * Makes AccessibleInstance instances.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class Make
{
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
     * @return AccessibleInstance
     */
    public static function accessible($instance)
    {
        if (is_object($instance)) {
            $reflector = new Reflector($instance);

            return new AccessibleInstance($instance, $reflector);
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
        if (is_string($singletonClass) && class_exists($singletonClass)) {
            return (new Reflector($singletonClass))->instantiateSingleton($singletonClass, $arguments);
        } else {
            throw new InvalidSingletonClassNameException('Invalid singleton class name provided.');
        }
    }

    /**
     * @param string $singletonClass The singleton class name.
     * @param array $arguments       Constructor arguments.
     *
     * @return AccessibleInstance
     */
    public static function accessibleInstance($singletonClass, $arguments = [])
    {
        $instance = self::instance($singletonClass, $arguments);

        return self::accessible($instance);
    }
}
