<?php

class PHP_Manipulator_NonAbstractHelper extends PHP_Manipulator_AbstractHelper
{

    public $init = false;
    
    public function init()
    {
        $this->init = true;
    }
}

class PHP_Manipulator_AbstractHelperTest extends TestCase
{

    /**
     * @covers PHP_Manipulator_AbstractHelper
     */
    public function testAbstractClassAndMethods()
    {
        $reflection = new ReflectionClass('PHP_Manipulator_AbstractHelper');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::__construct
     */
    public function testDefaultConstructor()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $this->assertEquals(array(), $abstractHelper->getOptions(), 'options don\'t match');
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::__construct
     * @covers PHP_Manipulator_AbstractHelper::init
     */
    public function testConstructorCallsInit()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $this->assertTrue($abstractHelper->init, 'init is not true');
    }

    /**
     * @return array
     */
    public function constructorOptionsProvider()
    {
        $data = array();

        $data[] = array(array());
        $data[] = array(array('baa' => 'foo'));
        $data[] = array(array('baa' => 'foo', 'blub' => 'bla'));

        return $data;
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::__construct
     * @dataProvider constructorOptionsProvider
     */
    public function testConstructorSetsOptions($options)
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper($options);
        $this->assertEquals($options, $abstractHelper->getOptions(), 'options don\'t match');
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::addOptions
     * @covers PHP_Manipulator_AbstractHelper::getOptions
     */
    public function testAddOptionsAndGetOptions()
    {
        $options = array(
            'baa' => 'foo',
            'blub' => 'bla',
        );
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper(array('foo' => 'bla'));
        $fluent = $abstractHelper->addOptions($options);
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');

        $this->assertEquals(3, count($abstractHelper->getOptions()), 'Wrong options count');
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::setOption
     * @covers PHP_Manipulator_AbstractHelper::getOption
     */
    public function testSetOptionAndGetOption()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $fluent = $abstractHelper->setOption('baa', 'foo');
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');
        $this->assertEquals('foo', $abstractHelper->getOption('baa'), 'Wrong value');
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::getOption
     * @covers PHP_Manipulator_Exception
     */
    public function testGetOptionThrowsExceptionOnNonExistingOption()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        try {
            $abstractHelper->getOption('foo');
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals("Option 'foo' not found", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::hasOption
     */
    public function testHasOption()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper(array('foo' => 'bla'));
        $this->assertTrue($abstractHelper->hasOption('foo'));
        $this->assertFalse($abstractHelper->hasOption('blub'));
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::getClassInstance
     */
    public function testGetClassInstanceWithAutoPrefix()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance('Dummy1', 'PHP_Manipulator_Temp_', true);
        $this->assertTrue(class_exists('PHP_Manipulator_Temp_Dummy1', false), 'Class not loaded');
        $this->assertType('PHP_Manipulator_Temp_Dummy1', $instance, 'Wrong type');
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::getClassInstance
     */
    public function testGetClassInstanceWithoutAutoPrefix()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance('PHP_Manipulator_Temp_Dummy2', '', false);
        $this->assertTrue(class_exists('PHP_Manipulator_Temp_Dummy2', false), 'Class not loaded');
        $this->assertType('PHP_Manipulator_Temp_Dummy2', $instance, 'Wrong type');
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::getClassInstance
     */
    public function testGetClassInstanceWithDirectClass()
    {
        $class = new PHP_Manipulator_Temp_Dummy2();
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance($class, '', false);
        $this->assertSame($class, $instance);
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::evaluateConstraint
     */
    public function testEvaluateTokenConstraintEvaluatesTokenConstraint()
    {
        PHP_Manipulator_TokenConstraint_Mock::$return = false;
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $token = PHP_Manipulator_Token::factory(array(T_WHITESPACE, "\n"));
        $result = $abstractHelper->evaluateConstraint('Mock', $token);
        $this->assertFalse($result);
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::evaluateContainerConstraint
     */
    public function testEvaluateContainerConstraintEvaluatesContainerConstraint()
    {
        PHP_Manipulator_ContainerConstraint_Mock::$return = false;
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $container = new PHP_Manipulator_TokenContainer();
        $result = $abstractHelper->evaluateContainerConstraint('Mock', $container);
        $this->assertFalse($result);
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::manipulateContainer
     */
    public function testManipulateContainerManipulatesContainer()
    {
        PHP_Manipulator_ContainerManipulator_Mock::$called = false;
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();

        $abstractHelper->manipulateContainer(
                'Mock',
                new PHP_Manipulator_TokenContainer()
        );

        $this->assertTrue(PHP_Manipulator_ContainerManipulator_Mock::$called);
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::manipulateToken
     */
    public function testManipulateTokenManipulatesToken()
    {
        PHP_Manipulator_TokenManipulator_Mock::$called = false;
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();

        $abstractHelper->manipulateToken(
                'Mock',
                PHP_Manipulator_Token::factory(array(T_WHITESPACE, "\n"))
        );

        $this->assertTrue(PHP_Manipulator_TokenManipulator_Mock::$called);
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::evaluateConstraint
     */
    public function testEvaluateConstraintThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $token = PHP_Manipulator_Token::factory(array(T_WHITESPACE, "\n"));
        $constraint = new stdClass();

        try {
            $abstractHelper->evaluateConstraint($constraint, $token);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals('constraint is not instance of PHP_Manipulator_TokenConstraint_Interface', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::evaluateContainerConstraint
     */
    public function testEvaluateContainterConstraintThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $container = new PHP_Manipulator_TokenContainer();
        $constraint = new stdClass();

        try {
            $abstractHelper->evaluateContainerConstraint($constraint, $container);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals('constraint is not instance of PHP_Manipulator_ContainerConstraint_Interface', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::manipulateContainer
     */
    public function testManipulateContainterConstraintThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $container = new PHP_Manipulator_TokenContainer();
        $manipulator = new stdClass();

        try {
            $abstractHelper->manipulateContainer($manipulator, $container);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals('manipulator is not instance of PHP_Manipulator_ContainerManipulator_Interface', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers PHP_Manipulator_AbstractHelper::manipulateToken
     */
    public function testManipulateTokenThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new PHP_Manipulator_NonAbstractHelper();
        $token = PHP_Manipulator_Token::factory(array(T_WHITESPACE, "\n"));
        $manipulator = new stdClass();

        try {
            $abstractHelper->manipulateToken($manipulator, $token);
            $this->fail('Expected exception not thrown');
        } catch (PHP_Manipulator_Exception $e) {
            $this->assertEquals('manipulator is not instance of PHP_Manipulator_TokenManipulator_Interface', $e->getMessage(), 'Wrong exception message');
        }
    }
}