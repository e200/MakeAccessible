<?php

use e200\MakeAccessible\Reflector;
use PHPUnit\Framework\TestCase;
use Tests\Greeter;

class ReflectorTest extends TestCase
{
    public function testReflect()
    {
        $this->assertInstanceOf(ReflectionClass::class, (new Reflector)->reflect(Greeter::class));
    }

    public function testIsAccessible()
    {
        $reflector = $this->getReflector();
        $reflectedMethod = $this->reflectMethod('hasName');
        $reflectedProperty = $this->reflectProperty('name');

        $this->assertFalse($reflector->isAccessible($reflectedMethod));
        $this->assertFalse($reflector->isAccessible($reflectedProperty));
    }

    public function testMakeAccessible()
    {
        $reflector = $this->getReflector();
        $inaccessibleMethod = $this->reflectMethod('hasName');

        $reflector->makeAccessible($inaccessibleMethod);

        $instance = $this->getInstance();

        $this->assertTrue($inaccessibleMethod->invoke($instance));
    }

    public function testMakeAccessibleIfNot()
    {
        $reflector = $this->getReflector();
        $inaccessibleMethod = $this->reflectMethod('hasName');

        $reflector->makeAccessibleIfNot($inaccessibleMethod);

        $instance = $this->getInstance();

        $this->assertTrue($inaccessibleMethod->invoke($instance));
    }

    public function getReflectedClass()
    {
        return new ReflectionClass(Greeter::class);
    }

    public function reflectMethod($method)
    {
        $reflectedClass = $this->getReflectedClass();

        return $reflectedClass->getMethod($method);
    }

    public function reflectProperty($property)
    {
        $reflectedClass = $this->getReflectedClass();

        return $reflectedClass->getProperty($property);
    }

    public function getReflector()
    {
        return new Reflector();
    }

    public function getInstance()
    {
        return new Greeter();
    }
}
