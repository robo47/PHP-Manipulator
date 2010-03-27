<?php

require_once 'PHP/Formatter/ContainerManipulator/Mock.php';

class PHP_Formatter_ContainerManipulator_MockTest extends PHPFormatterTestCase
{
    /**
     * @covers PHP_Formatter_ContainerManipulator_Mock
     */
    public function testDefaultConstruct()
    {
        $mock = new PHP_Formatter_ContainerManipulator_Mock();
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_Mock
     */
    public function testOptionViaConstructorWorks()
    {
        $mock = new PHP_Formatter_ContainerManipulator_Mock(array('return' => true));
        $container = new PHP_Formatter_TokenContainer();
        $this->assertTrue($mock->manipulate($container));

        $mock = new PHP_Formatter_ContainerManipulator_Mock(array('return' => false));
        $container = new PHP_Formatter_TokenContainer();
        $this->assertFalse($mock->manipulate($container));
    }

    /**
     * @covers PHP_Formatter_ContainerManipulator_Mock
     */
    public function testOptionViastaticVariableWorks()
    {
        PHP_Formatter_ContainerManipulator_Mock::$return = true;
        $mock = new PHP_Formatter_ContainerManipulator_Mock();
        $container = new PHP_Formatter_TokenContainer();
        $this->assertTrue($mock->manipulate($container));

        PHP_Formatter_ContainerManipulator_Mock::$return = false;
        $mock = new PHP_Formatter_ContainerManipulator_Mock();
        $container = new PHP_Formatter_TokenContainer();
        $this->assertFalse($mock->manipulate($container));
    }
}