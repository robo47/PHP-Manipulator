<?php

/**
 * @group __classname__
 */
class PHP_Formatter___classname__Test extends PHPFormatterTestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/__path__/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Formatter___classname__
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $this->markTestSkipped('not implemented yet');
        $manipulator = new PHP_Formatter___classname__();
        $manipulator->manipulate($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}