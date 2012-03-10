<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\ClosureFactory;
use PHP\Manipulator\Token;

/**
 * @group ClosureFactory
 */
class ClosureFactoryTest extends \Tests\TestCase
{

    /**
     * @covers PHP\Manipulator\ClosureFactory::getIsTypeClosure
     */
    public function testGetIsTypeClosure()
    {
        $closure = ClosureFactory::getIsTypeClosure(T_WHITESPACE);
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(new Token('  ', T_WHITESPACE)));
        $this->assertFalse($closure(new Token('blub', T_STRING)));
    }

    /**
     * @covers PHP\Manipulator\ClosureFactory::getIsTypeClosure
     */
    public function testGetIsTypeClosureWithArray()
    {
        $closure = ClosureFactory::getIsTypeClosure(array(T_WHITESPACE, T_COMMENT));
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(new Token('  ', T_WHITESPACE)));
        $this->assertTrue($closure(new Token('  ', T_COMMENT)));
        $this->assertFalse($closure(new Token('blub', T_STRING)));
    }

    /**
     * @covers PHP\Manipulator\ClosureFactory::getHasValueClosure
     */
    public function testGetHasValueClosure()
    {
        $closure = ClosureFactory::getHasValueClosure('  ');
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(new Token('  ', T_WHITESPACE)));
        $this->assertFalse($closure(new Token('blub', T_STRING)));
    }

    /**
     * @covers PHP\Manipulator\ClosureFactory::getHasValueClosure
     */
    public function testGetHasValueClosureWithArray()
    {
        $closure = ClosureFactory::getHasValueClosure(array('  ', 'foo'));
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(new Token('  ', T_WHITESPACE)));
        $this->assertTrue($closure(new Token('foo', T_WHITESPACE)));
        $this->assertFalse($closure(new Token('blub', T_STRING)));
        $this->assertFalse($closure(new Token('bla', T_STRING)));
    }

    /**
     * @covers PHP\Manipulator\ClosureFactory::getTypeAndValueClosure
     */
    public function testGetTypeAndValueClosure()
    {
        $closure = ClosureFactory::getTypeAndValueClosure(T_WHITESPACE, 'foo');
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertFalse($closure(new Token('  ', T_WHITESPACE)));
        $this->assertTrue($closure(new Token('foo', T_WHITESPACE)));
        $this->assertFalse($closure(new Token('blub', T_STRING)));
        $this->assertFalse($closure(new Token('foo', T_STRING)));
    }
}
