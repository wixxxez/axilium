<?php

namespace Easy\Tests\Collections\Linq;

use Easy\Collections\Linq\Expr\ClosureExpressionVisitor;
use Easy\Collections\Linq\ExpressionBuilder;
use PHPUnit_Framework_TestCase;

/**
 * @group DDC-1637
 */
class ClosureExpressionVisitorTest extends PHPUnit_Framework_TestCase
{

    private $visitor;
    private $builder;

    public function setUp()
    {
        $this->visitor = new ClosureExpressionVisitor();
        $this->builder = new ExpressionBuilder();
    }

    public function testGetObjectFieldValueIsAccessor()
    {
        $object = new TestObject(1, 2, true);

        $this->assertTrue($this->visitor->getObjectFieldValue($object, 'baz'));
    }

    public function testGetObjectFieldValueMagicCallMethod()
    {
        $object = new TestObject(1, 2, true, 3);

        $this->assertEquals(3, $this->visitor->getObjectFieldValue($object, 'qux'));
    }

    public function testWalkEqualsComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->eq("foo", 1));

        $this->assertTrue($closure(new TestObject(1)));
        $this->assertFalse($closure(new TestObject(2)));
    }

    public function testWalkNotEqualsComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->neq("foo", 1));

        $this->assertFalse($closure(new TestObject(1)));
        $this->assertTrue($closure(new TestObject(2)));
    }

    public function testWalkLessThanComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->lt("foo", 1));

        $this->assertFalse($closure(new TestObject(1)));
        $this->assertTrue($closure(new TestObject(0)));
    }

    public function testWalkLessThanEqualsComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->lte("foo", 1));

        $this->assertFalse($closure(new TestObject(2)));
        $this->assertTrue($closure(new TestObject(1)));
        $this->assertTrue($closure(new TestObject(0)));
    }

    public function testWalkGreaterThanEqualsComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->gte("foo", 1));

        $this->assertTrue($closure(new TestObject(2)));
        $this->assertTrue($closure(new TestObject(1)));
        $this->assertFalse($closure(new TestObject(0)));
    }

    public function testWalkGreaterThanComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->gt("foo", 1));

        $this->assertTrue($closure(new TestObject(2)));
        $this->assertFalse($closure(new TestObject(1)));
        $this->assertFalse($closure(new TestObject(0)));
    }

    public function testWalkInComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->in("foo", array(1, 2, 3)));

        $this->assertTrue($closure(new TestObject(2)));
        $this->assertTrue($closure(new TestObject(1)));
        $this->assertFalse($closure(new TestObject(0)));
    }

    public function testWalkNotInComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->notIn("foo", array(1, 2, 3)));

        $this->assertFalse($closure(new TestObject(1)));
        $this->assertFalse($closure(new TestObject(2)));
        $this->assertTrue($closure(new TestObject(0)));
        $this->assertTrue($closure(new TestObject(4)));
    }

    public function testWalkContainsComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->contains('foo', 'hello'));

        $this->assertTrue($closure(new TestObject('hello world')));
        $this->assertFalse($closure(new TestObject('world')));
    }

    public function testWalkAndCompositeExpression()
    {
        $closure = $this->visitor->walkCompositeExpression(
                $this->builder->andX(
                        $this->builder->eq("foo", 1), $this->builder->eq("bar", 1)
                )
        );

        $this->assertTrue($closure(new TestObject(1, 1)));
        $this->assertFalse($closure(new TestObject(1, 0)));
        $this->assertFalse($closure(new TestObject(0, 1)));
        $this->assertFalse($closure(new TestObject(0, 0)));
    }

    public function testWalkOrCompositeExpression()
    {
        $closure = $this->visitor->walkCompositeExpression(
                $this->builder->orX(
                        $this->builder->eq("foo", 1), $this->builder->eq("bar", 1)
                )
        );

        $this->assertTrue($closure(new TestObject(1, 1)));
        $this->assertTrue($closure(new TestObject(1, 0)));
        $this->assertTrue($closure(new TestObject(0, 1)));
        $this->assertFalse($closure(new TestObject(0, 0)));
    }

    public function testSortByFieldAscending()
    {
        $objects = array(new TestObject("b"), new TestObject("a"), new TestObject("c"));
        $sort = ClosureExpressionVisitor::sortByField("foo");

        usort($objects, $sort);

        $this->assertEquals("a", $objects[0]->getFoo());
        $this->assertEquals("b", $objects[1]->getFoo());
        $this->assertEquals("c", $objects[2]->getFoo());
    }

    public function testSortByFieldDescending()
    {
        $objects = array(new TestObject("b"), new TestObject("a"), new TestObject("c"));
        $sort = ClosureExpressionVisitor::sortByField("foo", -1);

        usort($objects, $sort);

        $this->assertEquals("c", $objects[0]->getFoo());
        $this->assertEquals("b", $objects[1]->getFoo());
        $this->assertEquals("a", $objects[2]->getFoo());
    }

    public function testSortDelegate()
    {
        $objects = array(new TestObject("a", "c"), new TestObject("a", "b"), new TestObject("a", "a"));
        $sort = ClosureExpressionVisitor::sortByField("bar", 1);
        $sort = ClosureExpressionVisitor::sortByField("foo", 1, $sort);

        usort($objects, $sort);

        $this->assertEquals("a", $objects[0]->getBar());
        $this->assertEquals("b", $objects[1]->getBar());
        $this->assertEquals("c", $objects[2]->getBar());
    }

    public function testArrayComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->eq("foo", 42));

        $this->assertTrue($closure(array('foo' => 42)));
    }

    public function testWalkRegexComparison()
    {
        $closure = $this->visitor->walkComparison($this->builder->regex('foo', '#a$#'));

        $this->assertTrue((bool) $closure(new TestObject('asdasda')));
        $this->assertFalse((bool) $closure(new TestObject('world')));
        
        $closure = $this->visitor->walkComparison($this->builder->regex('foo', '#[a-z]{7}#'));

        $this->assertTrue((bool) $closure(new TestObject('asdasda')));
        $this->assertFalse((bool) $closure(new TestObject('Wsdasda')));
        
        $closure = $this->visitor->walkComparison($this->builder->regex('foo', '#[A-Z]{3}[0-9]{1,5}$#'));

        $this->assertTrue((bool) $closure(new TestObject('ALP1')));
        $this->assertTrue((bool) $closure(new TestObject('ALP12')));
        $this->assertTrue((bool) $closure(new TestObject('ALP123')));
        $this->assertFalse((bool) $closure(new TestObject('Wsdasda')));
        $this->assertFalse((bool) $closure(new TestObject('AL123456')));
        $this->assertFalse((bool) $closure(new TestObject('ALP7123456')));
        
        $closure = $this->visitor->walkComparison($this->builder->regex('foo', '#^a.+a$#i'));

        $this->assertTrue((bool) $closure(new TestObject('angela')));
        $this->assertTrue((bool) $closure(new TestObject('AngelA')));
        $this->assertTrue((bool) $closure(new TestObject('Angela')));
        $this->assertTrue((bool) $closure(new TestObject('angelA')));
        $this->assertFalse((bool) $closure(new TestObject('angela ')));
        
        $closure = $this->visitor->walkComparison($this->builder->regex('foo', '#^a.+a$#'));

        $this->assertTrue((bool) $closure(new TestObject('angela')));
        $this->assertFalse((bool) $closure(new TestObject('angela ')));
        $this->assertFalse((bool) $closure(new TestObject('AngelA')));
        $this->assertFalse((bool) $closure(new TestObject('Angela')));
        $this->assertFalse((bool) $closure(new TestObject('angelA')));
        
        $closure = $this->visitor->walkComparison($this->builder->regex('foo', '#^\([0-9]{2}\)\s[0-9]{4}\-[0-9]{4,5}$#'));

        $this->assertTrue((bool) $closure(new TestObject('(83) 8822-5566')));
        $this->assertTrue((bool) $closure(new TestObject('(83) 8822-55669')));
        $this->assertFalse((bool) $closure(new TestObject('(83) 8822-5566 ')));
        $this->assertFalse((bool) $closure(new TestObject('(83) 8822-55669 ')));
        $this->assertFalse((bool) $closure(new TestObject('(83)8822-55669')));
        $this->assertFalse((bool) $closure(new TestObject('(83) 8822-556699')));
        $this->assertFalse((bool) $closure(new TestObject('(83) 882255669')));
        $this->assertFalse((bool) $closure(new TestObject('83 882255669')));
        $this->assertFalse((bool) $closure(new TestObject('83882255669')));
        
    }
}

class TestObject
{

    private $foo;
    private $bar;
    private $baz;
    private $qux;

    public function __construct($foo = null, $bar = null, $baz = null, $qux = null)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
        $this->qux = $qux;
    }

    public function __call($name, $arguments)
    {
        if ('getqux' === $name) {
            return $this->qux;
        }
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function getBar()
    {
        return $this->bar;
    }

    public function isBaz()
    {
        return $this->baz;
    }

}
