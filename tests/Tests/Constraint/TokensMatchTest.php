<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\Token;
use Tests\Constraint\TokensMatch;

/**
 * @group TokensMatch
 */
class TokensMatchTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Tests\Constraint\TokensMatch::__construct
     */
    public function testConstruct()
    {
        $token = new TokensMatch(new Token('foo'), true);
    }

    /**
     * @return array
     */
    public function tokensProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            new Token('foo', T_WHITESPACE, 5),
            new Token('foo', T_WHITESPACE, 5),
            true,
            true
        );

        # 1 different line
        $data[] = array(
            new Token('foo', T_WHITESPACE, 5),
            new Token('foo', T_WHITESPACE, 6),
            false,
            true
        );

        # 2 different line
        $data[] = array(
            new Token('foo', T_WHITESPACE, 5),
            new Token('foo', T_WHITESPACE, 6),
            true,
            false
        );

        # 3 different type
        $data[] = array(
            new Token('foo', null, 5),
            new Token('foo', T_WHITESPACE, 5),
            false,
            false
        );

        # 4 different type
        $data[] = array(
            new Token('foo', null, 5),
            new Token('foo', T_WHITESPACE, 5),
            false,
            true
        );

        # 5 different value
        $data[] = array(
            new Token('foo', null, 5),
            new Token('baa', null, 5),
            false,
            true
        );

        return $data;
    }

    /**
     * @dataProvider tokensProvider
     * @covers \Tests\Constraint\TokensMatch::evaluate
     * @covers \Tests\Constraint\TokensMatch::<protected>
     */
    public function testTokensMatch($other, $expected, $expectedEvaluationResult, $strict)
    {
        $count = new TokensMatch($expected, $strict);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other, '', true));
    }

    /**
     * @covers \Tests\Constraint\TokensMatch::toString
     */
    public function testToString()
    {
        $count = new TokensMatch(new Token('Foo'), true);
        $this->assertEquals('Token matches another Token', $count->toString());
    }

    /**
     * @covers \Tests\Constraint\TokensMatch::__construct
     */
    public function testConstructorThrowsExceptionIfExpectedIsNoToken()
    {
        try {
            $tokenContainerMatch = new TokensMatch('foo', false);
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument #1 of Tests\Constraint\TokensMatch::__construct() must be a PHP\Manipulator\Token', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \Tests\Constraint\TokensMatch::__construct
     */
    public function testConstructorThrowsExceptionIfStrictIsNoBoolean()
    {
        try {
            $tokenMatch = new TokensMatch(new Token(null), 'false');
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument #2 of Tests\Constraint\TokensMatch::__construct() must be a boolean', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \Tests\Constraint\TokensMatch::evaluate
     */
    public function testEvaluteThrowsExceptionIfOtherIsNoToken()
    {
        $tokenMatch = new TokensMatch(new Token(null), true);
        try {
            $tokenMatch->evaluate('string');
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Argument #1 of Tests\Constraint\TokensMatch::evaluate() must be a PHP\Manipulator\Token', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \Tests\Constraint\TokensMatch::failureDescription
     */
    public function testFailAndFailureDescriptionWithDifferentValues()
    {
        $expected = new Token('blub', null, '4');
        $other = new Token('bla', null, '4');
        $tokenMatch = new TokensMatch($expected, true);

        $message = PHP_EOL . \PHPUnit_Util_Diff::diff(
            (string) $expected,
            (string) $other
        );

        $message = 'Failed asserting that Tokens are different: [values]' . $message . '.';

        try {
            $tokenMatch->evaluate($other);
            $this->fail('no exception thrown');
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }

    /**
     * @covers \Tests\Constraint\TokensMatch::failureDescription
     */
    public function testFailAndFailureDescriptionWithDifferentTypes()
    {
        $expected = new Token('blub', null, '4');
        $other = new Token('blub', T_WHITESPACE, '4');
        $tokenMatch = new TokensMatch($expected, true);
        

        $message = PHP_EOL . \PHPUnit_Util_Diff::diff(
            (string) $expected,
            (string) $other
        );
        $message = 'Failed asserting that Tokens are different: [types]' . $message . '.';


        try {
            $tokenMatch->evaluate($other);
            $this->fail('no exception thrown');
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }

    /**
     * @covers \Tests\Constraint\TokensMatch::failureDescription
     */
    public function testFailAndFailureDescriptionWithDifferentLinenumbers()
    {
        $expected = new Token('blub', null, '5');
        $other = new Token('blub', null, '4');
        $tokenMatch = new TokensMatch($expected, true);
        

        $message = PHP_EOL . \PHPUnit_Util_Diff::diff(
            (string) $expected,
            (string) $other
        );
        $message = 'Failed asserting that Tokens are different: [linenumber]' . $message . '.';

        try {
            $tokenMatch->evaluate($other);
            $this->fail('no exception thrown');
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }
}