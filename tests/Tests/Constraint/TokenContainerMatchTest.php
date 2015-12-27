<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenContainer;
use PHPUnit_Framework_Exception;
use PHPUnit_Framework_ExpectationFailedException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Tests\Constraint\TokenContainerMatch
 */
class TokenContainerMatchTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $expected = TokenContainer::createEmptyContainer();
        $this->assertInstanceOf(TokenContainerMatch::class, new TokenContainerMatch($expected, true));
    }

    /**
     * @return array
     */
    public function containerProvider()
    {
        $data = [];

        $data[' strict #1'] = [
            TokenContainer::factory('<?php echo "foo"; ?>'),
            TokenContainer::factory('<?php echo "foo"; ?>'),
            true,
            true,
        ];

        $data['non-strict #1'] = [
            TokenContainer::factory('<?php echo "foo"; ?>'),
            TokenContainer::factory('<?php echo "foo"; ?>'),
            true,
            false,
        ];

        $data['strict #2'] = [
            TokenContainer::factory('<?php echo "baa"; ?>'),
            TokenContainer::factory('<?php echo "foo"; ?>'),
            false,
            true,
        ];

        $data['non-strict #2'] = [
            TokenContainer::factory('<?php echo "baa"; ?>'),
            TokenContainer::factory('<?php echo "foo"; ?>'),
            false,
            false,
        ];

        # 4
        $data['non-strict #3'] = [
            $c1 = TokenContainer::factory('<?php echo "baa"; ?>'),
            TokenContainer::factory('<?php echo "baa"; ?>'),
            false,
            true,
        ];

        // change a tokens linenumber
        $c1[1]->setLineNumber(0);

        $data['non-strict #4'] = [
            TokenContainer::factory('<?php echo "baa"; ?>'),
            TokenContainer::factory('<?php echo "baa"; '),
            false,
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider containerProvider
     *
     * @param TokenContainer $other
     * @param TokenContainer $expected
     * @param bool           $expectedEvaluationResult
     * @param bool           $strict
     */
    public function testTokenContainerMatch(
        TokenContainer $other,
        TokenContainer $expected,
        $expectedEvaluationResult,
        $strict
    ) {
        $count = new TokenContainerMatch($expected, $strict);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other, '', true));
    }

    public function testToString()
    {
        $count = new TokenContainerMatch(TokenContainer::factory(), true);
        $this->assertSame('TokenContainer matches another Container', $count->toString());
    }

    public function testConstructorThrowsExceptionIfStrictIsNotBoolean()
    {
        $this->setExpectedException(PHPUnit_Framework_Exception::class, 'must be a bool');
        new TokenContainerMatch(TokenContainer::createEmptyContainer(), 'false');
    }

    public function testEvaluateThrowsExceptionIfOtherIsNoTokenContainer()
    {
        $tokenContainerMatch = new TokenContainerMatch(TokenContainer::factory(), false);
        $this->setExpectedException(PHPUnit_Framework_Exception::class, 'must be a PHP\Manipulator\TokenContainer');
        $tokenContainerMatch->evaluate('string');
    }

    public function testFailAndFailureDescription()
    {
        $expected = TokenContainer::factory('<?php echo "foo"; ?>');
        $other    = TokenContainer::factory('<?php echo "foo"; /* foo */ ?>');

        $containerMatch = new TokenContainerMatch($expected, false);

        $this->setExpectedException(
            PHPUnit_Framework_ExpectationFailedException::class,
            'Failed asserting that Tokens are different:'
        );

        $containerMatch->evaluate($other);
    }
}
