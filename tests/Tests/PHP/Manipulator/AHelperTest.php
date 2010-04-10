<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class NonAbstractHelper extends AHelper
{
    
    public $init = false;
    
    public function init()
    {
        $this->init = true;
    }
}

class AHelperTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\AHelper
     */
    public function testAbstractClassAndMethods()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\AHelper');
        $this->assertTrue($reflection->isAbstract(), 'Class is not abstract');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::__construct
     */
    public function testDefaultConstructor()
    {
        $abstractHelper = new NonAbstractHelper();
        $this->assertEquals(array(), $abstractHelper->getOptions(), 'options don\'t match');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::__construct
     * @covers \PHP\Manipulator\AHelper::init
     */
    public function testConstructorCallsInit()
    {
        $abstractHelper = new NonAbstractHelper();
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
     * @covers \PHP\Manipulator\AHelper::__construct
     * @dataProvider constructorOptionsProvider
     */
    public function testConstructorSetsOptions($options)
    {
        $abstractHelper = new NonAbstractHelper($options);
        $this->assertEquals($options, $abstractHelper->getOptions(), 'options don\'t match');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::addOptions
     * @covers \PHP\Manipulator\AHelper::getOptions
     */
    public function testAddOptionsAndGetOptions()
    {
        $options = array(
            'baa' => 'foo',
            'blub' => 'bla',
        );
        $abstractHelper = new NonAbstractHelper(array('foo' => 'bla'));
        $fluent = $abstractHelper->addOptions($options);
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');

        $this->assertCount(3, $abstractHelper->getOptions(), 'Wrong options count');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::setOption
     * @covers \PHP\Manipulator\AHelper::getOption
     */
    public function testSetOptionAndGetOption()
    {
        $abstractHelper = new NonAbstractHelper();
        $fluent = $abstractHelper->setOption('baa', 'foo');
        $this->assertSame($fluent, $abstractHelper, 'No fluent interface');
        $this->assertEquals('foo', $abstractHelper->getOption('baa'), 'Wrong value');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getOption
     * @covers \Exception
     */
    public function testGetOptionThrowsExceptionOnNonExistingOption()
    {
        $abstractHelper = new NonAbstractHelper();
        try {
            $abstractHelper->getOption('foo');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Option 'foo' not found", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\AHelper::hasOption
     */
    public function testHasOption()
    {
        $abstractHelper = new NonAbstractHelper(array('foo' => 'bla'));
        $this->assertTrue($abstractHelper->hasOption('foo'));
        $this->assertFalse($abstractHelper->hasOption('blub'));
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithAutoPrefix()
    {
        $abstractHelper = new NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance('Dummy1', '\Tests\PHP\Manipulator\Temp\\', true);
        $this->assertTrue(class_exists('\Tests\PHP\Manipulator\Temp\Dummy1', false), 'Class not loaded');
        $this->assertType('\Tests\PHP\Manipulator\Temp\Dummy1', $instance, 'Wrong type');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithoutAutoPrefix()
    {
        $abstractHelper = new NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance('\Tests\PHP\Manipulator\Temp\Dummy2', '', false);
        $this->assertTrue(class_exists('\Tests\PHP\Manipulator\Temp\Dummy2', false), 'Class not loaded');
        $this->assertType('\Tests\PHP\Manipulator\Temp\Dummy2', $instance, 'Wrong type');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithDirectClass()
    {
        $class = new \Tests\PHP\Manipulator\Temp\Dummy2();
        $abstractHelper = new NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance($class, '', false);
        $this->assertSame($class, $instance);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::evaluateConstraint
     */
    public function testEvaluateTokenConstraintEvaluatesTokenConstraint()
    {
        \PHP\Manipulator\TokenConstraint\Mock::$return = false;
        $abstractHelper = new NonAbstractHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $result = $abstractHelper->evaluateConstraint('Mock', $token);
        $this->assertFalse($result);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::evaluateContainerConstraint
     */
    public function testEvaluateContainerConstraintEvaluatesContainerConstraint()
    {
        \PHP\Manipulator\ContainerConstraint\Mock::$return = false;
        $abstractHelper = new NonAbstractHelper();
        $container = new TokenContainer();
        $result = $abstractHelper->evaluateContainerConstraint('Mock', $container);
        $this->assertFalse($result);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::manipulateContainer
     */
    public function testManipulateContainerManipulatesContainer()
    {
        \PHP\Manipulator\ContainerManipulator\Mock::$called = false;
        $abstractHelper = new NonAbstractHelper();

        $abstractHelper->manipulateContainer(
                'Mock',
                new TokenContainer()
        );

        $this->assertTrue(\PHP\Manipulator\ContainerManipulator\Mock::$called);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::manipulateToken
     */
    public function testManipulateTokenManipulatesToken()
    {
        \PHP\Manipulator\TokenManipulator\Mock::$called = false;
        $abstractHelper = new NonAbstractHelper();

        $abstractHelper->manipulateToken(
                'Mock',
                Token::factory(array(T_WHITESPACE, "\n"))
        );

        $this->assertTrue(\PHP\Manipulator\TokenManipulator\Mock::$called);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::findTokens
     */
    public function testFindTokensFindsTokens()
    {
        $expectedResult = new \PHP\Manipulator\TokenFinder\Result();
        $finder = new \PHP\Manipulator\TokenFinder\Mock($expectedResult);
        $token = new Token('Foo');
        $container = new TokenContainer();
        $abstractHelper = new NonAbstractHelper();

        $actualResult = $abstractHelper->findTokens(
            $finder,
            $token,
            $container
        );

        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::evaluateConstraint
     */
    public function testEvaluateConstraintThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new NonAbstractHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $constraint = new \stdClass();

        try {
            $abstractHelper->evaluateConstraint($constraint, $token);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('constraint is not instance of \PHP\Manipulator\TokenConstraint', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\AHelper::findTokens
     */
    public function testFindTokensThrowsExceptionIfFinderIstNotValidFinder()
    {
        $abstractHelper = new NonAbstractHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $container = new TokenContainer();
        $constraint = new \stdClass();

        try {
            $abstractHelper->findTokens($constraint, $token, $container);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('finder is not instance of \PHP\Manipulator\TokenFinder', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\AHelper::evaluateContainerConstraint
     */
    public function testEvaluateContainterConstraintThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new NonAbstractHelper();
        $container = new TokenContainer();
        $constraint = new \stdClass();

        try {
            $abstractHelper->evaluateContainerConstraint($constraint, $container);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('constraint is not instance of \PHP\Manipulator\ContainerConstraint', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\AHelper::manipulateContainer
     */
    public function testManipulateContainterConstraintThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new NonAbstractHelper();
        $container = new TokenContainer();
        $manipulator = new \stdClass();

        try {
            $abstractHelper->manipulateContainer($manipulator, $container);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('manipulator is not instance of \PHP\Manipulator\ContainerManipulator', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\AHelper::manipulateToken
     */
    public function testManipulateTokenThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new NonAbstractHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $manipulator = new \stdClass();

        try {
            $abstractHelper->manipulateToken($manipulator, $token);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('manipulator is not instance of \PHP\Manipulator\TokenManipulator', $e->getMessage(), 'Wrong exception message');
        }
    }
}