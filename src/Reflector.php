<?php

namespace e200\MakeAccessible;

use ReflectionClass;

/**
  * Class Reflector.
  *
  * @author Eleandro Duzentos <eleandro@inbox.ru>
  */
 class Reflector
 {
     /**
      * Makes a new ReflectionClass instance.
      *
      * @return ReflectionClass
      */
     public static function reflect($class)
     {
         return new ReflectionClass($class);
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
 }
