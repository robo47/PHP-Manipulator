<?php

namespace Tests\PHP\Manipulator\TokenManipulator;

use PHP\Manipulator\TokenManipulator\__classname__;
use PHP\Manipulator\Token;

/**
 * @group TokenManipulator\__classname__
 */
class __classname__Test
extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_BOOLEAN_AND, "AND")),
            Token::factory(array(T_BOOLEAN_AND, "and")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers __completeclassname__
     */
    public function testManipulate($actualToken, $expectedToken, $strict)
    {
        $manipulator = new __classname__();
        $manipulator->manipulate($token);
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}