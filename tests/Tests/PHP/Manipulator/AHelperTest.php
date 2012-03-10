<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\ClosureFactory;


/**
 * @group AHelper
 */
class AHelperTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithAutoPrefix()
    {
        $abstractHelper = new AHelper();
        $instance = $abstractHelper->getClassInstance('Dummy1', 'Baa\\Foo\\', true);
        $this->assertTrue(class_exists('Baa\\Foo\\Dummy1', false), 'Class not loaded');
        $this->assertInstanceOf('Baa\\Foo\\Dummy1', $instance, 'Wrong type');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithoutAutoPrefix()
    {
        $abstractHelper = new AHelper();
        $instance = $abstractHelper->getClassInstance('Baa\\Foo\\Dummy2', '', false);
        $this->assertTrue(class_exists('Baa\\Foo\\Dummy2', false), 'Class not loaded');
        $this->assertInstanceOf('Baa\\Foo\\Dummy2', $instance, 'Wrong type');
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getClassInstance
     */
    public function testGetClassInstanceWithDirectClass()
    {
        $class = new \Baa\Foo\Dummy2();
        $abstractHelper = new AHelper();
        $instance = $abstractHelper->getClassInstance($class, '', false);
        $this->assertSame($class, $instance);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::evaluateConstraint
     */
    public function testEvaluateTokenConstraintEvaluatesTokenConstraint()
    {
        \Tests\Stub\TokenConstraintStub::$return = false;
        $abstractHelper = new AHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $result = $abstractHelper->evaluateConstraint(
            'Tests\\Stub\\TokenConstraintStub',
            $token,
            null,
            false
        );
        $this->assertFalse($result);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::runAction
     */
    public function testrunActionManipulatesContainer()
    {
        \Tests\Stub\ActionStub::$called = false;
        $abstractHelper = new AHelper();

        $abstractHelper->runAction(
            'Tests\\Stub\\ActionStub',
            new TokenContainer(),
            null,
            false
        );

        $this->assertTrue(\Tests\Stub\ActionStub::$called);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::manipulateToken
     */
    public function testManipulateTokenManipulatesToken()
    {
        \Tests\Stub\TokenManipulatorStub::$called = false;
        $abstractHelper = new AHelper();

        $abstractHelper->manipulateToken(
            'Tests\\Stub\\TokenManipulatorStub',
            Token::factory(array(T_WHITESPACE, "\n")),
            null,
            false
        );

        $this->assertTrue(\Tests\Stub\TokenManipulatorStub::$called);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::findTokens
     */
    public function testFindTokensFindsTokens()
    {
        $expectedResult = new \PHP\Manipulator\TokenFinder\Result();
        $finder = new \Tests\Stub\TokenFinderStub($expectedResult);
        $token = new Token('Foo');
        $container = new TokenContainer();
        $abstractHelper = new AHelper();

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
        $abstractHelper = new AHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $constraint = new \stdClass();

        try {
            $abstractHelper->evaluateConstraint($constraint, $token);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('constraint is not instance of PHP\\Manipulator\\TokenConstraint', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\AHelper::findTokens
     */
    public function testFindTokensThrowsExceptionIfFinderIstNotValidFinder()
    {
        $abstractHelper = new AHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $container = new TokenContainer();
        $constraint = new \stdClass();

        try {
            $abstractHelper->findTokens($constraint, $token, $container);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('finder is not instance of PHP\\Manipulator\\TokenFinder', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\AHelper::runAction
     */
    public function testManipulateContainterConstraintThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new AHelper();
        $container = new TokenContainer();
        $manipulator = new \stdClass();

        try {
            $abstractHelper->runAction($manipulator, $container);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('manipulator is not instance of PHP\\Manipulator\\Action', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\AHelper::manipulateToken
     */
    public function testManipulateTokenThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new AHelper();
        $token = Token::factory(array(T_WHITESPACE, "\n"));
        $manipulator = new \stdClass();

        try {
            $abstractHelper->manipulateToken($manipulator, $token);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('manipulator is not instance of PHP\Manipulator\TokenManipulator', $e->getMessage(), 'Wrong exception message');
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
        $ahelper = new AHelper();
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
        $ahelper = new AHelper();
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
        $ahelper = new AHelper();
        $this->assertSame($result, $ahelper->isType($token, $param), 'Wrong result');
    }


    /**
     * @return array
     */
    public function hasValueProvider()
    {
        $data = array();

        #0
        $data[] = array(
            Token::factory(array(T_COMMENT, 'foo')),
            'foo',
            true
        );

        #1
        $data[] = array(
            Token::factory(array(T_COMMENT, 'foo')),
            'baa',
            false
        );

        #2
        $data[] = array(
            Token::factory(array(T_COMMENT, 'foo')),
            array('baa', 'foo', 'blub'),
            true
        );

        #3
        $data[] = array(
            Token::factory(array(T_COMMENT, 'foo')),
            array('baa', 'blub', 'blubber'),
            false
        );


        return $data;
    }


    /**
     * @dataProvider hasValueProvider
     * @covers \PHP\Manipulator\AHelper::hasValue
     */
    public function testHasValue($token, $value, $result)
    {
        $ahelper = new AHelper();
        $this->assertSame($result, $ahelper->hasValue($token, $value), 'Wrong result');
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
        $ahelper = new AHelper();
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
        $ahelper = new AHelper();
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
        $ahelper = new AHelper();
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
        $ahelper = new AHelper();
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
        $ahelper = new AHelper();
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
        $ahelper = new AHelper();
        $this->assertSame($result, $ahelper->isQuestionMark($token), 'Wrong result');
    }

    /**
     * @return array
     */
    public function isFollowedByTokenTypeProvider()
    {
        $data = array();
        $path = '/AHelper/isFollowedByTokenType/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        $data[] = array(
            $container->getIterator()->seekToToken($container[21]),
            T_ECHO,
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT),
            false,
        );

        $data[] = array(
            $container->getIterator()->seekToToken($container[21]),
            T_ECHO,
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null),
            true,
        );

        return $data;
    }

    /**
     * @dataProvider isFollowedByTokenTypeProvider
     * @covers \PHP\Manipulator\AHelper::isFollowedByTokenType
     */
    public function testIsFollowedByTokenType($iterator, $followedByType, $allowedTokens, $expectedResult)
    {
        $ahelper = new AHelper();
        $startToken = $iterator->current();
        $result = $ahelper->isFollowedByTokenType(
            $iterator,
            $followedByType,
            $allowedTokens
        );
        $this->assertSame($expectedResult, $result);
        $this->assertSame($startToken, $iterator->current());
    }

    /**
     * @return array
     */
    public function isPrecededByTokenTypeProvider()
    {
        $data = array();
        $path = '/AHelper/isFollowedByTokenType/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            T_ELSE,
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT),
            false,
        );

        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            T_ELSE,
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null),
            true,
        );

        return $data;
    }

    /**
     * @dataProvider isPrecededByTokenTypeProvider
     * @covers \PHP\Manipulator\AHelper::isPrecededByTokenType
     */
    public function testIsPrecededByTokenType($iterator, $followedByType, $allowedTokens, $expectedResult)
    {
        $ahelper = new AHelper();
        $startToken = $iterator->current();
        $result = $ahelper->isPrecededByTokenType(
            $iterator,
            $followedByType,
            $allowedTokens
        );
        $this->assertSame($expectedResult, $result);
        $this->assertSame($startToken, $iterator->current());
    }

    /**
     * @return array
     */
    public function isPrecededByTokenMatchedByClosureProvider()
    {
        $data = array();
        $path = '/AHelper/isPrecededByTokenMatchedByClosure/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            ClosureFactory::getIsTypeClosure(T_ELSE),
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT),
            false,
        );

        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            ClosureFactory::getIsTypeClosure(T_ELSE),
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null),
            true,
        );

        return $data;
    }

    /**
     * @dataProvider isPrecededByTokenMatchedByClosureProvider
     * @param \Iterator $iterator
     * @param \Closure $closure
     * @param array $allowedTokens
     * @param boolean $expectedResult
     * @covers \PHP\Manipulator\AHelper::isPrecededByTokenMatchedByClosure
     */
    public function testIsPrecededByTokenMatchedByClosure($iterator, $closure, $allowedTokens, $expectedResult)
    {
        $ahelper = new AHelper();
        $startToken = $iterator->current();
        $result = $ahelper->isPrecededByTokenMatchedByClosure(
            $iterator,
            $closure,
            $allowedTokens
        );
        $this->assertSame($expectedResult, $result);
        $this->assertSame($startToken, $iterator->current());
    }

    /**
     * @return array
     */
    public function isFollowedByTokenValueProvider()
    {
        $data = array();
        $path = '/AHelper/isFollowedByTokenValue/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        $data[] = array(
            $container->getIterator()->seekToToken($container[21]),
            'echo',
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT),
            false,
        );

        $data[] = array(
            $container->getIterator()->seekToToken($container[21]),
            'echo',
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null),
            true,
        );

        return $data;
    }

    /**
     * @dataProvider isFollowedByTokenValueProvider
     * @covers \PHP\Manipulator\AHelper::isFollowedByTokenValue
     */
    public function testIsFollowedByTokenValue($iterator, $followedByType, $allowedTokens, $expectedResult)
    {
        $ahelper = new AHelper();
        $startToken = $iterator->current();
        $result = $ahelper->isFollowedByTokenValue(
            $iterator,
            $followedByType,
            $allowedTokens
        );
        $this->assertSame($expectedResult, $result);
        $this->assertSame($startToken, $iterator->current());
    }

    /**
     * @return array
     */
    public function isFollowedByTokenMatchedByClosureProvider()
    {
        $data = array();
        $path = '/AHelper/isFollowedByTokenMatchedByClosure/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        $data[] = array(
            $container->getIterator()->seekToToken($container[21]),
            ClosureFactory::getHasValueClosure('echo'),
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT),
            false,
        );

        $data[] = array(
            $container->getIterator()->seekToToken($container[21]),
            ClosureFactory::getHasValueClosure('echo'),
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null),
            true,
        );

        return $data;
    }

    /**
     * @dataProvider isFollowedByTokenMatchedByClosureProvider
     * @covers \PHP\Manipulator\AHelper::isFollowedByTokenMatchedByClosure
     */
    public function testIsFollowedByTokenMatchedByClosure($iterator, $closure, $allowedTokens, $expectedResult)
    {
        $ahelper = new AHelper();
        $startToken = $iterator->current();
        $result = $ahelper->isFollowedByTokenMatchedByClosure(
            $iterator,
            $closure,
            $allowedTokens
        );
        $this->assertSame($expectedResult, $result);
        $this->assertSame($startToken, $iterator->current());
    }

    /**
     * @return array
     */
    public function isPrecededByTokenValueProvider()
    {
        $data = array();
        $path = '/AHelper/isFollowedByTokenValue/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            'else',
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT),
            false,
        );

        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            'else',
            array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null),
            true,
        );

        return $data;
    }

    /**
     * @dataProvider isPrecededByTokenValueProvider
     * @covers \PHP\Manipulator\AHelper::isPrecededByTokenValue
     */
    public function testIsPrecededByTokenValue($iterator, $value, $allowedTokens, $expectedResult)
    {
        $ahelper = new AHelper();
        $startToken = $iterator->current();
        $result = $ahelper->isPrecededByTokenValue(
            $iterator,
            $value,
            $allowedTokens
        );
        $this->assertSame($expectedResult, $result);
        $this->assertSame($startToken, $iterator->current());
    }

    /**
     * @return array
     */
    public function isPrecededProvider()
    {
        $data = array();
        $path = '/AHelper/isPreceded/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        #0
        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            ClosureFactory::getIsTypeClosure(array(T_ELSE)),
            ClosureFactory::getIsTypeClosure(array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null)),
            $container[21],
            true
        );

        #1
        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            ClosureFactory::getIsTypeClosure(array(T_ELSE)),
            ClosureFactory::getIsTypeClosure(array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT)),
            null,
            false
        );

        #2
        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            ClosureFactory::getIsTypeClosure(array(T_FOR)),
            ClosureFactory::getIsTypeClosure(array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null)),
            null,
            false
        );

        return $data;
    }

    /**
     * @dataProvider isPrecededProvider
     * @covers \PHP\Manipulator\AHelper::isPreceded
     */
    public function testIsPreceded($iterator, $isSearchedToken, $isAllowedToken, $expectedFound, $expectedResult)
    {
        $startToken = $iterator->current();
        $ahelper = new AHelper();
        $actualResult = $ahelper->isPreceded($iterator, $isSearchedToken, $isAllowedToken, $actualFound);

        $this->assertSame($expectedFound, $actualFound, 'Found wrong token');
        $this->assertSame($expectedResult, $actualResult, 'Found wrong token');
        $this->assertSame($startToken, $iterator->current(), 'Iterator is not seeked to where it started');
    }

    /**
     * @return array
     */
    public function isFollowedProvider()
    {
        $data = array();
        $path = '/AHelper/isFollowed/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        #0
        $data[] = array(
            $container->getIterator()->seekToToken($container[21]),
            ClosureFactory::getIsTypeClosure(array(T_ECHO)),
            ClosureFactory::getIsTypeClosure(array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null)),
            $container[25],
            true
        );

        #1
        $data[] = array(
            $container->getIterator()->seekToToken($container[21]),
            ClosureFactory::getIsTypeClosure(array(T_ECHO)),
            ClosureFactory::getIsTypeClosure(array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT)),
            null,
            false
        );

        #2
        $data[] = array(
            $container->getIterator()->seekToToken($container[25]),
            ClosureFactory::getIsTypeClosure(array(T_FOR)),
            ClosureFactory::getIsTypeClosure(array(T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null)),
            null,
            false
        );

        return $data;
    }

    /**
     * @dataProvider isFollowedProvider
     * @covers \PHP\Manipulator\AHelper::isFollowed
     */
    public function testIsFollowed($iterator, $isSearchedToken, $isAllowedToken, $expectedFound, $expectedResult)
    {
        $startToken = $iterator->current();
        $ahelper = new AHelper();
        $actualResult = $ahelper->isFollowed($iterator, $isSearchedToken, $isAllowedToken, $actualFound);

        $this->assertSame($expectedFound, $actualFound, 'Found wrong token');
        $this->assertSame($expectedResult, $actualResult, 'Found wrong token');
        $this->assertSame($startToken, $iterator->current(), 'Iterator is not seeked to where it started');
    }

    /**
     * @return array
     */
    public function getMatchingBraceProvider()
    {
        $data = array();
        $path = '/AHelper/getMatchingBrace/';
        $container0 = $this->getContainerFromFixture($path . 'input0.php');
        $container1 = $this->getContainerFromFixture($path . 'input1.php');
        $container2 = $this->getContainerFromFixture($path . 'input2.php');

        #0 forwards normal brace
        $data[] = array(
            $container0->getIterator()->seekToToken($container0[3]),
            $container0[9]
        );

        #1 backwards normal brace
        $data[] = array(
            $container0->getIterator()->seekToToken($container0[9]),
            $container0[3]
        );

        #2 forwards curly brace
        $data[] = array(
            $container1->getIterator()->seekToToken($container1[7]),
            $container1[29]
        );

        #3 backwards curly brace
        $data[] = array(
            $container1->getIterator()->seekToToken($container1[29]),
            $container1[7]
        );

        #4 forwards square bracket
        $data[] = array(
            $container2->getIterator()->seekToToken($container2[2]),
            $container2[13]
        );

        #5 backwards square bracket
        $data[] = array(
            $container2->getIterator()->seekToToken($container2[13]),
            $container2[2]
        );

        return $data;
    }

    /**
     * @param  \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param  \PHP\Manipulator\Token $token
     *
     * @dataProvider getMatchingBraceProvider
     * @covers \PHP\Manipulator\AHelper::getMatchingBrace
     * @covers \PHP\Manipulator\AHelper::_nextToken
     */
    public function testGetMatchingBrace($iterator, $token)
    {
        $ahelper = new AHelper();
        $start = $iterator->current();
        $matchingBrace = $ahelper->getMatchingBrace($iterator);
        $this->assertTrue($iterator->valid(), 'Iterator is not valid');
        $this->assertSame($start, $iterator->current(), 'Iterator is not at starting-position');
        $this->assertSame($token, $matchingBrace);
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getMatchingBrace
     */
    public function testGetMatchingBraceWithoutAMatchingBrace()
    {
        $container = $this->getContainerFromFixture('/AHelper/getMatchingBrace/input0.php');
        $iterator = $container->getIterator()->seekToToken($container[3]);
        $container[9]->setValue('blub');

        $ahelper = new AHelper();

        $this->assertTrue($iterator->valid(), 'Iterator is not valid');
        $this->assertSame($container[3], $iterator->current(), 'Iterator is not at starting-position');
        $this->assertNull($ahelper->getMatchingBrace($iterator));
    }

    /**
     * @covers \PHP\Manipulator\AHelper::getMatchingBrace
     */
    public function testGetMatchingBraceThrowsExceptionIfIteratorIsNotAtABrace()
    {
        $container = $this->getContainerFromFixture('/AHelper/getMatchingBrace/input0.php');
        $iterator = $container->getIterator()->seekToToken($container[2]);

        $ahelper = new AHelper();

        try {
            $ahelper->getMatchingBrace($iterator);
            $this->fail('Expected Exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('Token is no brace like (,),{,},[ or ]', $e->getMessage(), 'Wrong exception message');
        }
    }


    /**
     * @return array
     */
    public function getNextMatchingTokenProvider()
    {
        $data = array();
        $path = '/AHelper/getNextMatchingToken/';
        $container = $this->getContainerFromFixture($path . 'input0.php');

        #0 Finding a Token
        $data[] = array(
            $container->getIterator(),
            function (Token $token) {
                if (null === $token->getType() && ')' === $token->getValue()) {
                    return true;
                } else {
                    return false;
                }
            },
            $container[6]
        );

        #1 Not finding a Token
        $data[] = array(
            $container->getIterator(),
            function (Token $token) {
                if (null === $token->getType() && '$' === $token->getValue()) {
                    return true;
                } else {
                    return false;
                }
            },
            null
        );

        return $data;
    }

    /**
     * @param  \PHP\Manipulator\TokenContainer\Iterator $iterator
     * @param  \Closure $closure
     * @param  \PHP\Manipulator\Token $token
     *
     * @dataProvider getNextMatchingTokenProvider
     * @covers \PHP\Manipulator\AHelper::getNextMatchingToken
     */
    public function testGetNextMatchingToken($iterator, $closure, $token)
    {
        $ahelper = new AHelper();
        $start = $iterator->current();

        $matchingToken = $ahelper->getNextMatchingToken($iterator, $closure);
        $this->assertTrue($iterator->valid(), 'Iterator is not valid');
        $this->assertSame($start, $iterator->current(), 'Iterator is not at starting-position');
        $this->assertSame($token, $matchingToken);
    }
}
