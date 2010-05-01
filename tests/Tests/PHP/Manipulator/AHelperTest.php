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

    /**
     * @return array
     */
    public function isColonProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, ':')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, ':')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(null, ';')),
            false
        );

        return $data;
    }

    /**
     * @dataProvider isColonProvider
     * @covers \PHP\Manipulator\AHelper::isColon
     */
    public function testIsColon($token, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isColon($token), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isCommaProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, ',')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, ',')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(null, ':')),
            false
        );

        return $data;
    }

    /**
     * @dataProvider isCommaProvider
     * @covers \PHP\Manipulator\AHelper::isComma
     */
    public function testIsComma($token, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isComma($token), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isTypeProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_COMMENT, "// some comment")),
            T_COMMENT,
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, "// some comment")),
            T_WHITESPACE,
            false
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, "// some comment")),
            array(T_WHITESPACE, T_CLOSE_TAG, T_COMMENT),
            true
        );

        #3
        $data[] = array(
            Token::factory(array(T_COMMENT, "// some comment")),
            array(T_WHITESPACE, T_CLOSE_TAG, T_DOC_COMMENT),
            false
        );


        return $data;
    }


    /**
     * @dataProvider isTypeProvider
     * @covers \PHP\Manipulator\AHelper::isType
     */
    public function testIsType($token, $param, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isType($token, $param), 'Wrong result');
    }


    /**
     * @return array
     */
    public function isClosingBraceProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, '(')),
            false
        );

        #1
        $data[] = array(
            Token::factory(array(null, ')')),
            true
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, '(')),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(T_COMMENT, ')')),
            false
        );


        return $data;
    }

    /**
     * @dataProvider isClosingBraceProvider
     * @covers \PHP\Manipulator\AHelper::isClosingBrace
     */
    public function testIsClosingBrace($token, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isClosingBrace($token), 'Wrong result');
    }


    /**
     * @return array
     */
    public function isClosingCurlyBraceProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, '{')),
            false
        );

        #1
        $data[] = array(
            Token::factory(array(null, '}')),
            true
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, '{')),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(T_COMMENT, '}')),
            false
        );


        return $data;
    }

    /**
     * @dataProvider isClosingCurlyBraceProvider
     * @covers \PHP\Manipulator\AHelper::isClosingCurlyBrace
     */
    public function testIsClosingCurlyBrace($token, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isClosingCurlyBrace($token), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isOpeningBraceProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, '(')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(null, ')')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, '(')),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(T_COMMENT, ')')),
            false
        );


        return $data;
    }

    /**
     * @dataProvider isOpeningBraceProvider
     * @covers \PHP\Manipulator\AHelper::isOpeningBrace
     */
    public function testIsOpeningBrace($token, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isOpeningBrace($token), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isOpeningCurlyBraceProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, '{')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(null, '}')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, '{')),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(T_COMMENT, '}')),
            false
        );


        return $data;
    }

    /**
     * @dataProvider isOpeningCurlyBraceProvider
     * @covers \PHP\Manipulator\AHelper::isOpeningCurlyBrace
     */
    public function testIsOpeningCurlyBrace($token, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isOpeningCurlyBrace($token), 'Wrong result');
    }


    /**
     * @return array
     */
    public function isSemicolonProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, ';')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, ';')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(null, ':')),
            false
        );

        return $data;
    }

    /**
     * @dataProvider isSemicolonProvider
     * @covers \PHP\Manipulator\AHelper::isSemicolon
     */
    public function testIsSemicolon($token, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isSemicolon($token), 'Wrong result');
    }


    /**
     * @return array
     */
    public function isQuestionMarkProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(null, '?')),
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_WHITESPACE, '?')),
            false
        );

        #2
        $data[] = array(
            Token::factory(array(null, ':')),
            false
        );

        #3
        $data[] = array(
            Token::factory(array(T_WHITESPACE, ':')),
            false
        );

        return $data;
    }

    /**
     * @dataProvider isQuestionMarkProvider
     * @covers \PHP\Manipulator\AHelper::isQuestionMark
     */
    public function testIsQuestionMark($token, $result)
    {
        $ahelper = new NonAbstractHelper();
        $this->assertSame($result, $ahelper->isQuestionMark($token), 'Wrong result');
    }
}