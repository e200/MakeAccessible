<?php

use PHPUnit\Framework\TestCase;
use Tests\Calc;
use Tests\Quazar;
use Tests\Singleton;
use e200\MakeAccessible\Exceptions\InvalidInstanceException;
use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;
use e200\MakeAccessible\Make;

/**
 * Class MakeTest.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class MakeTest extends TestCase
{
    public function testAccessible()
    {
        $instance = $this->getEncapsulatedClass();

        $accessibleInstance = Make::accessible($instance);

        $this->assertInstanceOf(Make::class, $accessibleInstance);
    }

    public function testInvalidObjectInstanceExceptionOnAccessibleInvalidInstance()
    {
        $invalidInstance = 'Pluto';

        $this->expectException(InvalidInstanceException::class);

        Make::accessible($invalidInstance);
    }

    public function testInvokeProtectedMethod()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertEquals('Hello Universe!', $accessibleInstance->helloUniverse());
    }

    public function testInvokePrivateMethod()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertEquals('Hi Eleandro!', $accessibleInstance->sayHi('Eleandro'));
    }

    public function testMethodNotFoundNotFoundExceptionOnInvokeMethod()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->expectException(MethodNotFoundException::class);

        $accessibleInstance->blackHole();
    }

    public function testGetProtectedProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertEquals('4.37 l/y', $accessibleInstance->alphaCentaurus);
    }

    public function testGetPrivateProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->assertEquals('299.792.458 m/s', $accessibleInstance->lightSpeed);
    }

    public function testPropertyNotFoundNotFoundExceptionOnAccessProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->expectException(PropertyNotFoundNotFoundException::class);

        $accessibleInstance->nebulosa;
    }

    public function testSetProtectedProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $expectedValue = 'Wow!!! Omega centaurus! :o';

        $accessibleInstance->alphaCentaurus = $expectedValue;

        $this->assertEquals($expectedValue, $accessibleInstance->alphaCentaurus);
    }

    public function testSetPrivateProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $expectedValue = 200;

        $accessibleInstance->lightSpeed = $expectedValue;

        $this->assertEquals($expectedValue, $accessibleInstance->lightSpeed);
    }

    public function testPropertyNotFoundNotFoundExceptionOnSetProperty()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $this->expectException(PropertyNotFoundNotFoundException::class);

        $accessibleInstance->nebulosa = 'Eye';
    }

    public function testSingletonClassInstance()
    {
        $instance = Make::instance(Singleton::class);

        $this->assertInstanceOf(Singleton::class, $instance);
        $this->assertEquals("Hi! I'm a singleton instance", $instance->getMessage());
    }

    public function testSingletonClassInstanceWithArgs()
    {
        $instance = Make::instance(Calc::class, [1, 2]);

        $this->assertInstanceOf(Calc::class, $instance);
        $this->assertEquals(3, $instance->sum());
    }

    public function getEncapsulatedClass()
    {
        return new Quazar();
    }

    public function getAccessibleInstance()
    {
        $instance = $this->getEncapsulatedClass();

        return Make::accessible($instance);
    }
}
