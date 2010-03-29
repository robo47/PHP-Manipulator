<?php

class PHP_Formatter_ContainerManipulator_MockTest extends PHPFormatterTestCase
{

    /**
     * @covers PHP_Formatter_ContainerManipulator_Mock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        PHP_Formatter_ContainerManipulator_Mock::$called = false;
        $mock = new PHP_Formatter_ContainerManipulator_Mock();
        $container = new PHP_Formatter_TokenContainer();
        $this->assertFalse(PHP_Formatter_ContainerManipulator_Mock::$called);
        $mock->manipulate($container);
        $this->assertTrue(PHP_Formatter_ContainerManipulator_Mock::$called);
    }
}