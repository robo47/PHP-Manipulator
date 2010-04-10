<?php

namespace Tests\Constraint;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenContainer;
use Tests\Constraint\TokenContainerMatch;

// @todo test faile-message and stuff
// @todo more tests and check diffs!
class TokenContainerMatchTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function containerProvider()
    {
        $data = array();

        # 0 strict
        $data[] = array(
            new TokenContainer('<?php echo "foo"; ?>'),
            new TokenContainer('<?php echo "foo"; ?>'),
            true,
            true
        );

        # 1 non-strict
        $data[] = array(
            new TokenContainer('<?php echo "foo"; ?>'),
            new TokenContainer('<?php echo "foo"; ?>'),
            true,
            false
        );

        # 2 strict
        $data[] = array(
            new TokenContainer('<?php echo "baa"; ?>'),
            new TokenContainer('<?php echo "foo"; ?>'),
            false,
            true
        );

        # 3 non-strict
        $data[] = array(
            new TokenContainer('<?php echo "baa"; ?>'),
            new TokenContainer('<?php echo "foo"; ?>'),
            false,
            false
        );

        // @todo create tokens with different line-numbers

        return $data;
    }

    /**
     * @dataProvider containerProvider
     * @covers \Tests\Constraint\TokenContainerMatch::evaluate
     * @covers \Tests\Constraint\TokenContainerMatch::<protected>
     */
    public function testTokenContainerMatch($other, $expected, $expectedEvaluationResult, $strict)
    {
        $count = new TokenContainerMatch($expected, $strict);
        $this->assertSame($expectedEvaluationResult, $count->evaluate($other));
    }

    /**
     * @covers \Tests\Constraint\TokenContainerMatch::toString
     */
    public function testToString()
    {
        $count = new TokenContainerMatch(new TokenContainer(), true);
        $this->assertEquals('TokenContainer matches another Container', $count->toString());
    }
}