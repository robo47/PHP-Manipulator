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
    public function run(TokenContainer $container)
    {
    }
}

/**
 * @group AHelper
 */
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
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithAutoPrefix()
    {
        $abstractHelper = new NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance('Dummy1', '\Baa\Foo\\', true);
        $this->assertTrue(class_exists('\Baa\Foo\Dummy1', false), 'Class not loaded');
        $this->assertType('\Baa\Foo\Dummy1', $instance, 'Wrong type');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithoutAutoPrefix()
    {
        $abstractHelper = new NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance('\Baa\Foo\Dummy2', '', false);
        $this->assertTrue(class_exists('\Baa\Foo\Dummy2', false), 'Class not loaded');
        $this->assertType('\Baa\Foo\Dummy2', $instance, 'Wrong type');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithDirectClass()
    {
        $class = new \Baa\Foo\Dummy2();
        $abstractHelper = new NonAbstractHelper();
        $instance = $abstractHelper->getClassInstance($class, '', false);
        $this->assertSame($class, $instance);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::evaluateConstraint
     */
    public function testEvaluateTokenConstraintEvaluatesTokenConstraint()
    {
        \Tests\Mock\TokenConstraintMock::$return = false;
        $abstractHelper = new NonAbstractHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $result = $abstractHelper->evaluateConstraint(
            '\Tests\Mock\TokenConstraintMock',
            $token,
            null,
            false
        );
        $this->assertFalse($result);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::evaluateContainerConstraint
     */
    public function testEvaluateContainerConstraintEvaluatesContainerConstraint()
    {
        \Tests\Mock\ContainerConstraintMock::$return = false;
        $abstractHelper = new NonAbstractHelper();
        $container = new TokenContainer();
        $result = $abstractHelper->evaluateContainerConstraint(
            '\Tests\Mock\ContainerConstraintMock',
            $container,
            null,
            false
        );
        $this->assertFalse($result);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::manipulateContainer
     */
    public function testManipulateContainerManipulatesContainer()
    {
        \Tests\Mock\ActionMock::$called = false;
        $abstractHelper = new NonAbstractHelper();

        $abstractHelper->manipulateContainer(
            '\Tests\Mock\ActionMock',
            new TokenContainer(),
            null,
            false
        );

        $this->assertTrue(\Tests\Mock\ActionMock::$called);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::manipulateToken
     */
    public function testManipulateTokenManipulatesToken()
    {
        \Tests\Mock\TokenManipulatorMock::$called = false;
        $abstractHelper = new NonAbstractHelper();

        $abstractHelper->manipulateToken(
            '\Tests\Mock\TokenManipulatorMock',
            Token::factory(array(T_WHITESPACE, "\n")),
            null,
            false
        );

        $this->assertTrue(\Tests\Mock\TokenManipulatorMock::$called);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::findTokens
     */
    public function testFindTokensFindsTokens()
    {
        $expectedResult = new \PHP\Manipulator\TokenFinder\Result();
        $finder = new \Tests\Mock\TokenFinderMock($expectedResult);
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
            $this->assertEquals('manipulator is not instance of \PHP\Manipulator\Action', $e->getMessage(), 'Wrong exception message');
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