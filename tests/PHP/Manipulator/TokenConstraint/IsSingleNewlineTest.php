<?php
namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\IsSingleNewline;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint_IsSingleNewline
 */
class IsSingleNewlineTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        # 0
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n")),
            true
        );

        # 1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r")),
            true
        );

        # 2
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\r\n")),
            true
        );

        # 3
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n\n")),
            false
        );

        # 4
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n\r")),
            false
        );

        # 5
        $data[] = array(
            Token::factory(array(T_WHITESPACE, " \n")),
            false
        );

        # 6
        $data[] = array(
            Token::factory("\n"),
            true
        );

        #7
        $data[] = array(
            Token::factory("\r\n"),
            true
        );

        #8
        $data[] = array(
            Token::factory("\r"),
            true
        );

        #9
        $data[] = array(
            Token::factory("\n\r"),
            false
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP\Manipulator\TokenConstraint\IsSingleNewline::evaluate
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new IsSingleNewline();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}