<?php

/**
 * @group ContainerConstraint_Mock
 */
class PHP_Formatter_ContainerConstraint_MockTest extends TestCase
{

    /**
     * @covers PHP_Formatter_ContainerConstraint_Mock
     */
    public function testDefaultConstruct()
    {
        $mock = new PHP_Formatter_ContainerConstraint_Mock();
    }

    /**
     * @covers PHP_Formatter_ContainerConstraint_Mock
     */
    public function testOptionViaConstructorWorks()
    {
        $mock = new PHP_Formatter_ContainerConstraint_Mock(array('return' => true));
        $container = new PHP_Formatter_TokenContainer();
        $this->assertTrue($mock->evaluate($container));

        $mock = new PHP_Formatter_ContainerConstraint_Mock(array('return' => false));
        $container = new PHP_Formatter_TokenContainer();
        $this->assertFalse($mock->evaluate($container));
    }

    /**
     * @covers PHP_Formatter_ContainerConstraint_Mock
     */
    public function testOptionViastaticVariableWorks()
    {
        PHP_Formatter_ContainerConstraint_Mock::$return = true;
        $mock = new PHP_Formatter_ContainerConstraint_Mock();
        $container = new PHP_Formatter_TokenContainer();
        $this->assertTrue($mock->evaluate($container));

        PHP_Formatter_ContainerConstraint_Mock::$return = false;
        $mock = new PHP_Formatter_ContainerConstraint_Mock();
        $container = new PHP_Formatter_TokenContainer();
        $this->assertFalse($mock->evaluate($container));
    }
}