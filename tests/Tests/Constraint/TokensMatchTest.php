<?php

namespace Tests\Constraint;

use PHP\Manipulator\Token;
use PHPUnit_Framework_Exception;
use PHPUnit_Framework_ExpectationFailedException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Tests\Constraint\TokensMatch
 */
class TokensMatchTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf(TokensMatch::class, new TokensMatch(Token::createFromValue('foo'), true));
    }

    /**
     * @return array
     */
    public function tokensProvider()
    {
        $data = [];

        # 0
        $data[] = [
            Token::create('foo', T_WHITESPACE, 5),
            Token::create('foo', T_WHITESPACE, 5),
            true,
            true,
        ];

        # 1 different line
        $data[] = [
            Token::create('foo', T_WHITESPACE, 5),
            Token::create('foo', T_WHITESPACE, 6),
            false,
            true,
        ];

        # 2 different line
        $data[] = [
            Token::create('foo', T_WHITESPACE, 5),
            Token::create('foo', T_WHITESPACE, 6),
            true,
            false,
        ];

        # 3 different type
        $data[] = [
            Token::create('foo', null, 5),
            Token::create('foo', T_WHITESPACE, 5),
            false,
            false,
        ];

        # 4 different type
        $data[] = [
            Token::create('foo', null, 5),
            Token::create('foo', T_WHITESPACE, 5),
            false,
            true,
        ];

        # 5 different value
        $data[] = [
            Token::create('foo', null, 5),
            Token::create('baa', null, 5),
            false,
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider tokensProvider
     *
     * @param Token $other
     * @param Token $expected
     * @param bool  $expectedEvaluationResult
     * @param bool  $strict
     */
    public function testTokensMatch(Token $other, Token $expected, $expectedEvaluationResult, $strict)
    {
        $count = new TokensMatch($expected, $strict);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other, '', true));
    }

    public function testToString()
    {
        $count = new TokensMatch(Token::create('Foo'), true);
        $this->assertSame('Token matches another Token', $count->toString());
    }

    public function testConstructorThrowsExceptionIfStrictIsNoBoolean()
    {
        $this->setExpectedException(PHPUnit_Framework_Exception::class, 'must be a bool');
        new TokensMatch(Token::createFromValue('<?php'), 'false');
    }

    public function testEvaluteThrowsExceptionIfOtherIsNoToken()
    {
        $this->setExpectedException(PHPUnit_Framework_Exception::class, 'must be a PHP\Manipulator\Token');
        $tokenMatch = new TokensMatch(Token::createFromValue('<?php'), true);
        $tokenMatch->evaluate('string');
    }

    public function testFailAndFailureDescriptionWithDifferentValues()
    {
        $expected   = Token::create('blub', null, 4);
        $other      = Token::create('bla', null, 4);
        $tokenMatch = new TokensMatch($expected, true);

        $this->setExpectedException(
            PHPUnit_Framework_ExpectationFailedException::class,
            'Failed asserting that Tokens are different: [values]'
        );

        $tokenMatch->evaluate($other);
    }

    public function testFailAndFailureDescriptionWithDifferentTypes()
    {
        $expected   = Token::create('blub', null, 4);
        $other      = Token::create('blub', T_WHITESPACE, 4);
        $tokenMatch = new TokensMatch($expected, true);
        $this->setExpectedException(
            PHPUnit_Framework_ExpectationFailedException::class,
            'Failed asserting that Tokens are different: [types]'
        );

        $tokenMatch->evaluate($other);
    }

    public function testFailAndFailureDescriptionWithDifferentLinenumbers()
    {
        $expected   = Token::create('blub', null, 5);
        $other      = Token::create('blub', null, 4);
        $tokenMatch = new TokensMatch($expected, true);
        $this->setExpectedException(
            PHPUnit_Framework_ExpectationFailedException::class,
            'Failed asserting that Tokens are different: [linenumber]'
        );

        $tokenMatch->evaluate($other);
    }
}
