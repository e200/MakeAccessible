<?php

use e200\MakeAccessible\Exceptions\InvalidObjectInstanceException;
use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;

use Tests\Quazar;
use PHPUnit\Framework\TestCase;
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

        $this->expectException(InvalidObjectInstanceException::class);

        Make::accessible($invalidInstance);
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

        return Make::accessible($instance);
    }
}
