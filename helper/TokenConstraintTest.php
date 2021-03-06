<?php

namespace Tests\PHP\Manipulator\TokenConstraint;

use PHP\Manipulator\TokenConstraint\__classname__;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group TokenConstraint\__classname__
 */
class __classname__Test
extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_WHITESPACE, "\n")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers __completeclassname__
     */
    public function testEvaluate($token, $result)
    {
        $constraint = new __classname__();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}