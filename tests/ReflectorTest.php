<?php

use e200\MakeAccessible\Reflector;
use PHPUnit\Framework\TestCase;
use Tests\Yomi;

class ReflectorTest extends TestCase
{
    public function testReflect()
    {
        $this->assertInstanceOf(ReflectionClass::class, Reflector::reflect(Yomi::class));
    }

    public function testIsAccessible()
    {
        $reflector = $this->getReflector();
        $reflectedMethod = $this->getReflectedMethod();
        $reflectedProperty = $this->getReflectedProperty();

        $this->assertFalse($reflector->isAccessible($reflectedMethod));
        $this->assertTrue($reflector->isAccessible($reflectedProperty));
    }

    public function testMakeAccessible()
    {
        $reflector = $this->getReflector();
        $reflectedInaccessibleMethod = $this->getReflectedMethod();

        $reflector->makeAccessible($reflectedInaccessibleMethod);

        ////////////////////
        // It's necessary //
        ////////////////////
        $instance = $this->getYomi();

        $this->assertTrue($reflectedInaccessibleMethod->invoke($instance));
    }

    public function testMakeAccessibleIfNot()
    {
        $reflector = $this->getReflector();
        $reflectedInaccessibleMethod = $this->getReflectedMethod();

        $reflector->makeAccessibleIfNot($reflectedInaccessibleMethod);

        ////////////////////
        // It's necessary //
        ////////////////////
        $instance = $this->getYomi();

        $this->assertTrue($reflectedInaccessibleMethod->invoke($instance));
    }

    public function getReflectedClass()
    {
        return new ReflectionClass(Yomi::class);
    }

    public function getReflectedMethod()
    {
        $reflectedClass = $this->getReflectedClass();

        return $reflectedClass->getMethod('isNickName');
    }

    public function getReflectedProperty()
    {
        $reflectedClass = $this->getReflectedClass();

        return $reflectedClass->getProperty('name');
    }

    public function getReflector()
    {
        return new Reflector();
    }

    public function getYomi()
    {
        return new Yomi();
    }
}
