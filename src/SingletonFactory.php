<?php

namespace e200\MakeAccessible;

use e200\MakeAccessible\Exceptions\NonSingletonClassException;

/**
 * Class SingletonFactory.
 *
 * Makes instances from singleton classes.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class SingletonFactory
{
    /**
     * @param string $singletonClass
     * @param array  $arguments      Constructor arguments.
     *
     * @throws NonSingletonClassException
     *
     * @return object
     */
    public static function make($singletonClass, $arguments = [])
    {
        $reflector = new Reflector();

        // Getting the reflected `$singletonClass`.
        $refClass = $reflector->reflect($singletonClass);

        // Getting the constructor of the `$refClass`.
        $constructor = $refClass->getConstructor();

        /*
         * Every singleton class has a protected or private constructor.
         *
         * If our class constructor is null, that means our class hasn't a
         * constructor, what also means that our class isn't a singleton class.
         *
         * In this case we throw a `NonSingletonClassException`.
         */
        if (is_null($constructor)) {
            throw new NonSingletonClassException('Trying to instantiate a non singleton class.');
        } else {
            /*
             * Here, we're instantiating our class without invoke the constructor.
             *
             * Since we have the class instance, we can now reflect this instance,
             * make the constructor accessible and then invoke it, we're making
             * the exactly same process that php makes to instantiate a normal
             * class, but we're doing it with a singleton class, amazing uh???
             */
            $instance = $refClass->newInstanceWithoutConstructor();

            // First we make accessible our constructor (since is protected or private)
            $reflector->makeAccessible($constructor);

            // Next put `$arguments` into the constructor and then we invoke it.
            $constructor->invokeArgs($instance, $arguments);

            return $instance;
        }
    }
}
