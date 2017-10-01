<?php

use PHPUnit\Framework\TestCase;
use Tests\Calc;
use Tests\Quazar;
use Tests\Singleton;
use e200\MakeAccessible\Exceptions\InvalidInstanceException;
use e200\MakeAccessible\Exceptions\MethodNotFoundException;
use e200\MakeAccessible\Exceptions\PropertyNotFoundNotFoundException;
use e200\MakeAccessible\Exceptions\InvalidSingletonClassNameException;
use e200\MakeAccessible\Make;
use e200\MakeAccessible\AccessibleInstance;

/**
 * Class MakeTest.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class MakeTest extends TestCase
{
    /**
     * @covers Make::accessible()
     */
    public function testAccessible()
    {
        $instance = $this->getEncapsulatedClass();

        $accessibleInstance = Make::accessible($instance);

        $this->assertInstanceOf(AccessibleInstance::class, $accessibleInstance);
    }

    /**
     * @covers Make::accessible()
     */
    public function testInvalidObjectInstanceExceptionOnInvalidInstanceProvided()
    {
        $invalidInstance = 'Pluto';

        $this->expectException(InvalidInstanceException::class);

        Make::accessible($invalidInstance);
    }

    /**
     * @covers Make::instance()
     */
    public function testMakeSingletonInstance()
    {
        $instance = Make::instance(Singleton::class);

        $this->assertInstanceOf(Singleton::class, $instance);
        $this->assertEquals("Works!", $instance->getMessage());
    }

    /**
     * @covers Make::instance()
     */
    public function testSingletonClassInstanceWithArgs()
    {
        $instance = Make::instance(Calc::class, [1, 2]);

        $this->assertInstanceOf(Calc::class, $instance);
        $this->assertEquals(3, $instance->sum());
    }

    /**
     * @covers Make::instance()
     */
    public function testWrong()
    {
        $this->expectException(InvalidSingletonClassNameException::class);
        Make::instance(200);
    }

    /**
     * @covers Make::accessibleInstance()
     */
    public function testAccessibleInstance()
    {
        $instance = Make::accessibleInstance(Singleton::class);
        
        $this->assertTrue($instance->accessible());
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
