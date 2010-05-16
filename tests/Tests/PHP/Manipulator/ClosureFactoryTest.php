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
        $closure = ClosureFactory::getIsTypeClosure(array(T_WHITESPACE));
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(new Token('  ', T_WHITESPACE)));
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
}