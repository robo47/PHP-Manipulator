<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\MatcherFactory;
use PHP\Manipulator\Token;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\MatcherFactory
 */
class MatcherFactoryTest extends TestCase
{
    public function testGetIsTypeClosure()
    {
        $closure = MatcherFactory::createIsTypeMatcher(T_WHITESPACE);
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(Token::createFromValueAndType('  ', T_WHITESPACE)));
        $this->assertFalse($closure(Token::createFromValueAndType('blub', T_STRING)));
    }

    public function testGetIsTypeClosureWithArray()
    {
        $closure = MatcherFactory::createIsTypeMatcher([T_WHITESPACE, T_COMMENT]);
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(Token::createFromValueAndType('  ', T_WHITESPACE)));
        $this->assertTrue($closure(Token::createFromValueAndType('  ', T_COMMENT)));
        $this->assertFalse($closure(Token::createFromValueAndType('blub', T_STRING)));
    }

    public function testGetHasValueClosure()
    {
        $closure = MatcherFactory::createHasValueMatcher('  ');
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(Token::createFromValueAndType('  ', T_WHITESPACE)));
        $this->assertFalse($closure(Token::createFromValueAndType('blub', T_STRING)));
    }

    public function testGetHasValueClosureWithArray()
    {
        $closure = MatcherFactory::createHasValueMatcher(['  ', 'foo']);
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertTrue($closure(Token::createFromValueAndType('  ', T_WHITESPACE)));
        $this->assertTrue($closure(Token::createFromValueAndType('foo', T_WHITESPACE)));
        $this->assertFalse($closure(Token::createFromValueAndType('blub', T_STRING)));
        $this->assertFalse($closure(Token::createFromValueAndType('bla', T_STRING)));
    }

    public function testGetTypeAndValueClosure()
    {
        $closure = MatcherFactory::getTypeAndValueClosure(T_WHITESPACE, 'foo');
        $this->assertValidTokenMatchingClosure($closure);
        $this->assertFalse($closure(Token::createFromValueAndType('  ', T_WHITESPACE)));
        $this->assertTrue($closure(Token::createFromValueAndType('foo', T_WHITESPACE)));
        $this->assertFalse($closure(Token::createFromValueAndType('blub', T_STRING)));
        $this->assertFalse($closure(Token::createFromValueAndType('foo', T_STRING)));
    }
}
