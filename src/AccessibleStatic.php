<?php

namespace e200\MakeAccessible;

use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;
use ReflectionClass;


/**
 * AccessibleStatic.
 *
 * Makes encapsulated singleton members accessible.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class AccessibleStatic
{
    /** @var object */
    protected static $instance;

    /** @var Reflector */
    protected static $reflector;

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
    public static function __callStatic($name, $arguments)
    {
        $refClass = self::$reflector->getReflectedClass();
    }
}
