<?php

/**
 * @group __classname__
 */
class PHP_Formatter___classname__Test extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function manipluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_BOOLEAN_AND, "AND")),
            PHP_Formatter_Token::factory(array(T_BOOLEAN_AND, "and")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider manipluateProvider
     * @covers PHP_Formatter___classname__::manipulate
     */
    public function testManipulate($token, $newToken, $strict)
    {
        $this->markTestSkipped('not implemented yet');
        $manipulator = new PHP_Formatter___classname__();
        $manipulator->manipulate($token);
        $this->assertTokenMatch($token, $newToken, $strict);
    }
}