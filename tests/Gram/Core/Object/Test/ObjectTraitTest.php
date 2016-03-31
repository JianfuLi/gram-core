<?php
namespace Gram\Core\Object\Test;

use Gram\Core\Object\Mapper\MapperInterface;
use Gram\Core\Object\ObjectTrait;

class UnitTestObject
{
    use ObjectTrait;

    public $int1 = 0;
    public $int2 = 0;
    public $int3 = -1;
    public $int4 = 0;

    public $string1 = '';
    public $string2 = '';

    public $double1 = 0.0;
    public $double2 = 0.0;
    public $float1 = 0.0;
    public $float2 = 0.0;

    public $bool1 = false;
    public $bool2 = false;
    public $bool3 = false;
    public $bool4 = false;

    public $array1 = null;
    public $array2 = null;

    public $datetime1 = null;
    public $datetime2 = null;

    public $array3 = null;
    public $array4 = null;
    public $array5 = null;

    public $instance1 = null;

    /**
     * @param MapperInterface $m
     */
    protected static function mapping(MapperInterface $m)
    {
        $m->map('int1', 'int2', 'int3')->int()
            ->map('int4')->int()
            ->map('string1', 'string2')->string()
            ->map('double1', 'double2')->double()
            ->map('float1', 'float2')->float()
            ->map('bool1', 'bool2', 'bool3', 'bool4')->bool()
            ->map('array1', 'array2')->arr()
            ->map('datetime1', 'datetime2')->dateTime()
            ->map('array3', 'array4', 'array5')->instance('ArrayObject')
            ->map('instance1')->instance('Gram\Core\Object\Test\UnitTestObject');
    }
}

class ObjectTraitTest extends \PHPUnit_Framework_TestCase
{
    function testAssembleInt()
    {
        $actual = UnitTestObject::assemble([
            'int1' => 1,
            'int2' => '1',
            'int3' => 'abc',
            'int4' => true,
        ]);

        $this->assertTrue(1 === $actual->int1);
        $this->assertTrue(1 === $actual->int2);
        $this->assertTrue(0 === $actual->int3);
        $this->assertTrue(1 === $actual->int4);
    }

    function testAssembleString()
    {
        $actual = UnitTestObject::assemble([
            'string1' => 1,
            'string2' => '1',
        ]);

        $this->assertTrue('1' === $actual->string1);
        $this->assertTrue('1' === $actual->string2);

    }

    function testAssembleDoubleAndFloat()
    {
        $actual = UnitTestObject::assemble([
            'double1' => 1.2,
            'double2' => '1.1',
            'float1'  => 'abc',
            'float2'  => '1.3',
        ]);

        $this->assertTrue(1.2 === $actual->double1);
        $this->assertTrue(1.1 === $actual->double2);
        $this->assertTrue(0.0 === $actual->float1);
        $this->assertTrue(1.3 === $actual->float2);
    }

    function testAssembleBoolean()
    {
        $actual = UnitTestObject::assemble([
            'bool1' => 2,
            'bool2' => 'true',
            'bool3' => true,
            'bool4' => '0',
        ]);

        $this->assertTrue(true === $actual->bool1);
        $this->assertTrue(true === $actual->bool2);
        $this->assertTrue(true === $actual->bool3);
        $this->assertTrue(false === $actual->bool4);
    }

    function testAssembleArray()
    {
        $actual = UnitTestObject::assemble([
            'array1' => 2,
            'array2' => [[]],
        ]);

        $this->assertTrue([2] === $actual->array1);
        $this->assertTrue([[]] === $actual->array2);
    }

    function testAssembleDateTime()
    {
        $actual = UnitTestObject::assemble([
            'datetime1' => '2015-12-21 01:23:45',
            'datetime2' => new \DateTime('2015-12-21'),
        ]);

        $this->assertEquals(new \DateTime('2015-12-21 01:23:45'), $actual->datetime1);
        $this->assertEquals(new \DateTime('2015-12-21'), $actual->datetime2);
    }

    function testAssembleArrayObject()
    {
        $actual = UnitTestObject::assemble([
            'array3' => [2, 3],
            'array4' => new \ArrayObject([3]),
            'array5' => 4,
        ]);

        $this->assertEquals(new \ArrayObject([2, 3]), $actual->array3);
        $this->assertEquals(new \ArrayObject([3]), $actual->array4);
        $this->assertEquals(new \ArrayObject([4]), $actual->array5);
    }

    function testAssembleInstance()
    {
        $instance1 = new UnitTestObject();
        $actual = UnitTestObject::assemble([
            'instance1' => $instance1,
        ]);

        $this->assertTrue($instance1 === $actual->instance1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testAssembleInstanceRaiseException()
    {
        UnitTestObject::assemble([
            'instance1' => new \stdClass(),
        ]);
    }

    function testAssembleNull()
    {
        $actual = UnitTestObject::assemble([
            'int1'      => null,
            'string1'   => null,
            'double1'   => null,
            'float1'    => null,
            'bool1'     => null,
            'array1'    => null,
            'datetime1' => null,
            'instance1' => null,
        ]);

        $this->assertTrue(0 === $actual->int1);
        $this->assertTrue(null === $actual->string1);
        $this->assertTrue(0.0 === $actual->double1);
        $this->assertTrue(0.0 === $actual->float1);
        $this->assertTrue(false === $actual->bool1);
        $this->assertTrue([] === $actual->array1);
        $this->assertTrue(null === $actual->datetime1);
        $this->assertTrue(null === $actual->instance1);
    }
}