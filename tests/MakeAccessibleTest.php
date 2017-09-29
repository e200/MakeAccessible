<?php

use e200\MakeAccessible\Exceptions\InvalidObjectInstanceException;
use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;
use Tests\Quazar;
use PHPUnit\Framework\TestCase;
use e200\MakeAccessible\MakeAccessible;

class MakeAccessibleTest extends TestCase
{
    public function testMake()
    {
        $instance = $this->getEncapsulatedClass();

        $accessibleInstance = MakeAccessible::make($instance);

        $this->assertInstanceOf(MakeAccessible::class, $accessibleInstance);
    }

    public function testInvalidObjectInstanceExceptionOnMakeInvalidInstance()
    {
        $invalidInstance = 'Pluto';

        $this->expectException(InvalidObjectInstanceException::class);

        MakeAccessible::make($invalidInstance);
    }

    public function testInvokeProtectedMethod()
    {
        $accessibleInstance = $this->getAccessibleClass();

        $this->assertEquals('Hello my friend! :)', $accessibleInstance->hello());
    }

    public function testMethodNotFoundNotFoundExceptionOnInvokeMethod()
    {
        $accessibleInstance = $this->getAccessibleClass();

        $this->expectException(MethodNotFoundException::class);

        $accessibleInstance->BlackHole();
    }

    public function testGetProtectedProperty()
    {
        $accessibleInstance = $this->getAccessibleClass();

        $this->assertEquals('4.37 light/year', $accessibleInstance->alphaCentaurus);
    }

    public function testPropertyNotFoundNotFoundExceptionOnAccessProperty()
    {
        $accessibleInstance = $this->getAccessibleClass();

        $this->expectException(PropertyNotFoundNotFoundException::class);

        $accessibleInstance->nebulosa;
    }

    public function testsSetProtectedProperty()
    {
        $accessibleInstance = $this->getAccessibleClass();

        $expectedValue = 'Wow!!! Omega centaurus! :o';

        $accessibleInstance->alphaCentaurus = $expectedValue;

        $this->assertEquals($expectedValue, $accessibleInstance->alphaCentaurus);
    }

    public function testPropertyNotFoundNotFoundExceptionOnSetProperty()
    {
        $accessibleInstance = $this->getAccessibleClass();

        $this->expectException(PropertyNotFoundNotFoundException::class);

        $accessibleInstance->nebulosa = 'Eye';
    }

    public function getEncapsulatedClass()
    {
        return new Quazar();
    }

    public function getAccessibleClass()
    {
        $instance = $this->getEncapsulatedClass();

        return MakeAccessible::make($instance);
    }
}
