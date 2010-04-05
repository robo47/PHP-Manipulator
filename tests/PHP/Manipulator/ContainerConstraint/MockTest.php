<?php

/**
 * @group ContainerConstraint_Mock
 */
class PHP_Manipulator_ContainerConstraint_MockTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_ContainerConstraint_Mock
     */
    public function testDefaultConstruct()
    {
        $mock = new PHP_Manipulator_ContainerConstraint_Mock();
    }

    /**
     * @covers PHP_Manipulator_ContainerConstraint_Mock
     */
    public function testOptionViaConstructorWorks()
    {
        $mock = new PHP_Manipulator_ContainerConstraint_Mock(array('return' => true));
        $container = new PHP_Manipulator_TokenContainer();
        $this->assertTrue($mock->evaluate($container));

        $mock = new PHP_Manipulator_ContainerConstraint_Mock(array('return' => false));
        $container = new PHP_Manipulator_TokenContainer();
        $this->assertFalse($mock->evaluate($container));
    }

    /**
     * @covers PHP_Manipulator_ContainerConstraint_Mock
     */
    public function testOptionViastaticVariableWorks()
    {
        PHP_Manipulator_ContainerConstraint_Mock::$return = true;
        $mock = new PHP_Manipulator_ContainerConstraint_Mock();
        $container = new PHP_Manipulator_TokenContainer();
        $this->assertTrue($mock->evaluate($container));

        PHP_Manipulator_ContainerConstraint_Mock::$return = false;
        $mock = new PHP_Manipulator_ContainerConstraint_Mock();
        $container = new PHP_Manipulator_TokenContainer();
        $this->assertFalse($mock->evaluate($container));
    }
}