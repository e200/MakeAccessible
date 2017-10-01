<?php

namespace e200\MakeAccessible;

use e200\MakeAccessible\Exceptions\NonSingletonClassException;
use ReflectionClass;

/**
  * Class Reflector.
  *
  * @author Eleandro Duzentos <eleandro@inbox.ru>
  */
 class Reflector
 {
     /** @var \ReflectionClass */
     protected $refClass;

     /**
      * Reflector constructor.
      *
      * @param object|string The class name.
      */
     public function __construct($class)
     {
         $this->refClass = $this->reflectClass($class);
     }

     /**
      * Makes a new ReflectionClass instance.
      *
      * @return ReflectionClass
      */
     public function reflectClass($class)
     {
         return new ReflectionClass($class);
     }

     /**
      * Gets the reflected class instance.
      *
      * @return ReflectionClass
      */
     public function getReflectedClass()
     {
         return $this->refClass;
     }

     /**
      * Checks if a member is accessible.
      *
      * @param \ReflectionMethod|\ReflectionProperty $refObject
      *
      * @return bool
      */
     public function isAccessible($refObject)
     {
         return $refObject->isPublic();
     }

     /**
      * Makes a member accessible if its inaccessible.
      *
      * @param \ReflectionMethod|\ReflectionProperty $refObject
      */
     public function makeAccessibleIfInaccessible($refObject)
     {
         if (!$this->isAccessible($refObject)) {
             $this->makeAccessible($refObject);
         }
     }

     /**
      * Makes a member accessible.
      *
      * @param \ReflectionMethod|\ReflectionProperty $refObject
      */
     public function makeAccessible($refObject)
     {
         $refObject->setAccessible(true);
     }

     /**
      * @param string $singletonClass
      * @param array $arguments       Constructor arguments.
      *
      * @throws NonSingletonClassException
      *
      * @return object
      */
     public function instantiateSingleton($singletonClass, $arguments = [])
     {
         // Getting the reflection class that represents the `$singletonClass`.
         $refClass = $this->reflectClass($singletonClass);

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
             $this->makeAccessible($constructor);

             // Next put `$arguments` into the constructor and then we invoke it.
             $constructor->invokeArgs($instance, $arguments);

             return $instance;
         }
     }
 }
