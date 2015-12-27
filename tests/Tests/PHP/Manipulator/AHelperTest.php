<?php

namespace Tests\PHP\Manipulator;

use Closure;
use PHP\Manipulator\AHelper;
use PHP\Manipulator\MatcherFactory;
use PHP\Manipulator\Exception\HelperException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;
use PHP\Manipulator\TokenFinder\Result;
use stdClass;
use Tests\Stub\ActionStub;
use Tests\Stub\TokenFinderStub;
use Tests\Stub\TokenManipulatorStub;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\AHelper
 */
class AHelperTest extends TestCase
{
    public function testrunActionManipulatesContainer()
    {
        ActionStub::$called = false;
        $abstractHelper     = new AHelper();

        $abstractHelper->runAction(
            ActionStub::class,
            TokenContainer::createEmptyContainer()
        );

        $this->assertTrue(ActionStub::$called);
    }

    public function testManipulateTokenManipulatesToken()
    {
        TokenManipulatorStub::$called = false;
        $abstractHelper               = new AHelper();

        $abstractHelper->manipulateToken(
            TokenManipulatorStub::class,
            Token::createFromMixed([T_WHITESPACE, "\n"]),
            null
        );

        $this->assertTrue(TokenManipulatorStub::$called);
    }

    public function testFindTokensFindsTokens()
    {
        $expectedResult = new Result();
        $finder         = new TokenFinderStub($expectedResult);
        $token          = Token::createFromValue('Foo');
        $container      = TokenContainer::createEmptyContainer();
        $abstractHelper = new AHelper();

        $actualResult = $abstractHelper->findTokens(
            $finder,
            $token,
            $container
        );

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testFindTokensThrowsExceptionIfFinderIstNotValidFinder()
    {
        $abstractHelper = new AHelper();
        $token          = Token::createFromMixed([T_WHITESPACE, "\n"]);
        $container      = TokenContainer::createEmptyContainer();
        $constraint     = new stdClass();

        $this->setExpectedException(
            HelperException::class,
            '',
            HelperException::FINDER_IS_NOT_INSTANCE_OF_TOKENFINDER
        );
        $abstractHelper->findTokens($constraint, $token, $container);
    }

    public function testManipulateContainterConstraintThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new AHelper();
        $container      = TokenContainer::createEmptyContainer();
        $manipulator    = new stdClass();

        $this->setExpectedException(
            HelperException::class,
            '',
            HelperException::ACTION_IS_NOT_INSTANCE_OF_ACTION
        );
        $abstractHelper->runAction($manipulator, $container);
    }

    public function testManipulateTokenThrowsExceptionIfConstraintIstNotValidConstraint()
    {
        $abstractHelper = new AHelper();
        $token          = Token::createFromMixed([T_WHITESPACE, "\n"]);
        $this->setExpectedException(
            HelperException::class,
            '',
            HelperException::MANIPULATOR_IS_NOT_INSTANCE_OF_TOKEN_MANIPULATOR
        );
        $abstractHelper->manipulateToken(new stdClass(), $token);
    }

    /**
     * @return array
     */
    public function isFollowedByTokenTypeProvider()
    {
        $data      = [];
        $path      = '/AHelper/isFollowedByTokenType/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        $data[] = [
            $container->getIterator()->seekToToken($container[21]),
            T_ECHO,
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT],
            false,
        ];

        $data[] = [
            $container->getIterator()->seekToToken($container[21]),
            T_ECHO,
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null],
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider isFollowedByTokenTypeProvider
     *
     * @param TokenContainerIterator $iterator
     * @param array                  $followedByType
     * @param array                  $allowedTokens
     * @param bool                   $expectedResult
     */
    public function testIsFollowedByTokenType(
        TokenContainerIterator $iterator,
        $followedByType,
        $allowedTokens,
        $expectedResult
    ) {
        $ahelper    = new AHelper();
        $startToken = $iterator->current();
        $result     = $ahelper->isFollowedByTokenType(
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
        $data      = [];
        $path      = '/AHelper/isFollowedByTokenType/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            T_ELSE,
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT],
            false,
        ];

        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            T_ELSE,
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null],
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider isPrecededByTokenTypeProvider
     *
     * @param TokenContainerIterator $iterator
     * @param array                  $followedByType
     * @param array                  $allowedTokens
     * @param bool                   $expectedResult
     */
    public function testIsPrecededByTokenType(
        TokenContainerIterator $iterator,
        $followedByType,
        $allowedTokens,
        $expectedResult
    ) {
        $ahelper    = new AHelper();
        $startToken = $iterator->current();
        $result     = $ahelper->isPrecededByTokenType(
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
        $data      = [];
        $path      = '/AHelper/isPrecededByTokenMatchedByClosure/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            MatcherFactory::createIsTypeMatcher(T_ELSE),
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT],
            false,
        ];

        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            MatcherFactory::createIsTypeMatcher(T_ELSE),
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null],
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider isPrecededByTokenMatchedByClosureProvider
     *
     * @param TokenContainerIterator $iterator
     * @param Closure                $closure
     * @param array                  $allowedTokens
     * @param bool                   $expectedResult
     */
    public function testIsPrecededByTokenMatchedByClosure(
        TokenContainerIterator $iterator,
        Closure $closure,
        $allowedTokens,
        $expectedResult
    ) {
        $ahelper    = new AHelper();
        $startToken = $iterator->current();
        $result     = $ahelper->isPrecededByTokenMatchedByClosure(
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
        $data      = [];
        $path      = '/AHelper/isFollowedByTokenValue/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        $data[] = [
            $container->getIterator()->seekToToken($container[21]),
            'echo',
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT],
            false,
        ];

        $data[] = [
            $container->getIterator()->seekToToken($container[21]),
            'echo',
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null],
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider isFollowedByTokenValueProvider
     *
     * @param TokenContainerIterator $iterator
     * @param array                  $followedByType
     * @param array                  $allowedTokens
     * @param bool                   $expectedResult
     */
    public function testIsFollowedByTokenValue(
        TokenContainerIterator $iterator,
        $followedByType,
        $allowedTokens,
        $expectedResult
    ) {
        $ahelper    = new AHelper();
        $startToken = $iterator->current();
        $result     = $ahelper->isFollowedByTokenValue(
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
        $data      = [];
        $path      = '/AHelper/isFollowedByTokenMatchedByClosure/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        $data[] = [
            $container->getIterator()->seekToToken($container[21]),
            MatcherFactory::createHasValueMatcher('echo'),
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT],
            false,
        ];

        $data[] = [
            $container->getIterator()->seekToToken($container[21]),
            MatcherFactory::createHasValueMatcher('echo'),
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null],
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider isFollowedByTokenMatchedByClosureProvider
     *
     * @param TokenContainerIterator $iterator
     * @param Closure                $closure
     * @param array                  $allowedTokens
     * @param bool                   $expectedResult
     */
    public function testIsFollowedByTokenMatchedByClosure(
        TokenContainerIterator $iterator,
        Closure $closure,
        $allowedTokens,
        $expectedResult
    ) {
        $ahelper    = new AHelper();
        $startToken = $iterator->current();
        $result     = $ahelper->isFollowedByTokenMatchedByClosure(
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
        $data      = [];
        $path      = '/AHelper/isFollowedByTokenValue/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            'else',
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT],
            false,
        ];

        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            'else',
            [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null],
            true,
        ];

        return $data;
    }

    /**
     * @dataProvider isPrecededByTokenValueProvider
     *
     * @param TokenContainerIterator $iterator
     * @param string                 $value
     * @param array                  $allowedTokens
     * @param bool                   $expectedResult
     */
    public function testIsPrecededByTokenValue(
        TokenContainerIterator $iterator,
        $value,
        $allowedTokens,
        $expectedResult
    ) {
        $ahelper    = new AHelper();
        $startToken = $iterator->current();
        $result     = $ahelper->isPrecededByTokenValue(
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
        $data      = [];
        $path      = '/AHelper/isPreceded/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        #0
        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            MatcherFactory::createIsTypeMatcher([T_ELSE]),
            MatcherFactory::createIsTypeMatcher([T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null]),
            $container[21],
            true,
        ];

        #1
        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            MatcherFactory::createIsTypeMatcher([T_ELSE]),
            MatcherFactory::createIsTypeMatcher([T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]),
            null,
            false,
        ];

        #2
        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            MatcherFactory::createIsTypeMatcher([T_FOR]),
            MatcherFactory::createIsTypeMatcher([T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null]),
            null,
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isPrecededProvider
     *
     * @param TokenContainerIterator $iterator
     * @param Closure                $isSearchedToken
     * @param Closure                $isAllowedToken
     * @param bool                   $expectedFound
     * @param bool                   $expectedResult
     */
    public function testIsPreceded(
        TokenContainerIterator $iterator,
        Closure $isSearchedToken,
        Closure $isAllowedToken,
        $expectedFound,
        $expectedResult
    ) {
        $startToken   = $iterator->current();
        $ahelper      = new AHelper();
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
        $data      = [];
        $path      = '/AHelper/isFollowed/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        #0
        $data[] = [
            $container->getIterator()->seekToToken($container[21]),
            MatcherFactory::createIsTypeMatcher([T_ECHO]),
            MatcherFactory::createIsTypeMatcher([T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null]),
            $container[25],
            true,
        ];

        #1
        $data[] = [
            $container->getIterator()->seekToToken($container[21]),
            MatcherFactory::createIsTypeMatcher([T_ECHO]),
            MatcherFactory::createIsTypeMatcher([T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]),
            null,
            false,
        ];

        #2
        $data[] = [
            $container->getIterator()->seekToToken($container[25]),
            MatcherFactory::createIsTypeMatcher([T_FOR]),
            MatcherFactory::createIsTypeMatcher([T_WHITESPACE, T_COMMENT, T_DOC_COMMENT, null]),
            null,
            false,
        ];

        return $data;
    }

    /**
     * @dataProvider isFollowedProvider
     *
     * @param TokenContainerIterator $iterator
     * @param Closure                $isSearchedToken
     * @param Closure                $isAllowedToken
     * @param Token                  $expectedFound
     * @param bool                   $expectedResult
     */
    public function testIsFollowed(
        TokenContainerIterator $iterator,
        $isSearchedToken,
        $isAllowedToken,
        $expectedFound,
        $expectedResult
    ) {
        $startToken   = $iterator->current();
        $ahelper      = new AHelper();
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
        $data       = [];
        $path       = '/AHelper/getMatchingBrace/';
        $container0 = $this->getContainerFromFixture($path.'input0.php');
        $container1 = $this->getContainerFromFixture($path.'input1.php');
        $container2 = $this->getContainerFromFixture($path.'input2.php');

        #0 forwards normal brace
        $data[] = [
            $container0->getIterator()->seekToToken($container0[3]),
            $container0[9],
        ];

        #1 backwards normal brace
        $data[] = [
            $container0->getIterator()->seekToToken($container0[9]),
            $container0[3],
        ];

        #2 forwards curly brace
        $data[] = [
            $container1->getIterator()->seekToToken($container1[7]),
            $container1[29],
        ];

        #3 backwards curly brace
        $data[] = [
            $container1->getIterator()->seekToToken($container1[29]),
            $container1[7],
        ];

        #4 forwards square bracket
        $data[] = [
            $container2->getIterator()->seekToToken($container2[2]),
            $container2[13],
        ];

        #5 backwards square bracket
        $data[] = [
            $container2->getIterator()->seekToToken($container2[13]),
            $container2[2],
        ];

        return $data;
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param Token                  $token
     *
     * @dataProvider getMatchingBraceProvider
     */
    public function testGetMatchingBrace(TokenContainerIterator $iterator, $token)
    {
        $ahelper       = new AHelper();
        $start         = $iterator->current();
        $matchingBrace = $ahelper->getMatchingBrace($iterator);
        $this->assertTrue($iterator->valid(), 'Iterator is not valid');
        $this->assertSame($start, $iterator->current(), 'Iterator is not at starting-position');
        $this->assertSame($token, $matchingBrace);
    }

    public function testGetMatchingBraceWithoutAMatchingBrace()
    {
        $container = $this->getContainerFromFixture('/AHelper/getMatchingBrace/input0.php');
        $iterator  = $container->getIterator()->seekToToken($container[3]);
        $container[9]->setValue('blub');

        $ahelper = new AHelper();

        $this->assertTrue($iterator->valid(), 'Iterator is not valid');
        $this->assertSame($container[3], $iterator->current(), 'Iterator is not at starting-position');
        $this->assertNull($ahelper->getMatchingBrace($iterator));
    }

    public function testGetMatchingBraceThrowsExceptionIfIteratorIsNotAtABrace()
    {
        $container = $this->getContainerFromFixture('/AHelper/getMatchingBrace/input0.php');
        $iterator  = $container->getIterator()->seekToToken($container[2]);
        $ahelper   = new AHelper();

        $this->setExpectedException(HelperException::class, '', HelperException::UNSUPPORTED_BRACE_EXCEPTION);

        $ahelper->getMatchingBrace($iterator);
    }

    /**
     * @return array
     */
    public function getNextMatchingTokenProvider()
    {
        $data      = [];
        $path      = '/AHelper/getNextMatchingToken/';
        $container = $this->getContainerFromFixture($path.'input0.php');

        #0 Finding a Token
        $data[] = [
            $container->getIterator(),
            function (Token $token) {
                if (null === $token->getType() && ')' === $token->getValue()) {
                    return true;
                } else {
                    return false;
                }
            },
            $container[6],
        ];

        #1 Not finding a Token
        $data[] = [
            $container->getIterator(),
            function (Token $token) {
                if (null === $token->getType() && '$' === $token->getValue()) {
                    return true;
                } else {
                    return false;
                }
            },
            null,
        ];

        return $data;
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param Closure                $closure
     * @param Token                  $token
     *
     * @dataProvider getNextMatchingTokenProvider
     */
    public function testGetNextMatchingToken($iterator, $closure, $token)
    {
        $ahelper = new AHelper();
        $start   = $iterator->current();

        $matchingToken = $ahelper->getNextMatchingToken($iterator, $closure);
        $this->assertTrue($iterator->valid(), 'Iterator is not valid');
        $this->assertSame($start, $iterator->current(), 'Iterator is not at starting-position');
        $this->assertSame($token, $matchingToken);
    }
}
