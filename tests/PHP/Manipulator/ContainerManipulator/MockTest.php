<?php

/**
 * @group ContainerManipulator_Mock
 */
class PHP_Manipulator_ContainerManipulator_MockTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_ContainerManipulator_Mock
     */
    public function testCallingManipulateSetsCalledToTrue()
    {
        PHP_Manipulator_ContainerManipulator_Mock::$called = false;
        $mock = new PHP_Manipulator_ContainerManipulator_Mock();
        $container = new PHP_Manipulator_TokenContainer();
        $this->assertFalse(PHP_Manipulator_ContainerManipulator_Mock::$called);
        $mock->manipulate($container);
        $this->assertTrue(PHP_Manipulator_ContainerManipulator_Mock::$called);
    }
}