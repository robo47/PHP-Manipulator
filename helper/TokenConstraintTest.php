<?php

/**
 * @group __classname__
 */
class PHP_Formatter___classname__Test extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();

        #0
        $data[] = array(
            PHP_Formatter_Token::factory(array(T_WHITESPACE, "\n")),
            true
        );

        return $data;
    }

    /**
     * @dataProvider evaluateProvider
     * @covers PHP_Formatter___classname__
     */
    public function testEvaluate($token, $result)
    {
        $this->markTestSkipped('not implemented yet');
        $constraint = new PHP_Formatter___classname__();
        $this->assertSame($result, $constraint->evaluate($token), 'Wrong result');
    }
}