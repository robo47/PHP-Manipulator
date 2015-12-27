<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use Exception;
use PHP\Manipulator\Exception\TokenFinderException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\IncludeAndRequireFinder;
use PHP\Manipulator\TokenFinder\Result;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenFinder\IncludeAndRequireFinder
 */
class IncludeAndRequireFinderTest extends TestCase
{
    /**
     * @return array
     */
    public function findProvider()
    {
        $data = [];

        #0
        $data[] = [
            $container = TokenContainer::factory('<?php include "foo.php"; ?>'),
            $container[1], // include
            Result::factory([$container[1], $container[2], $container[3], $container[4]]),
        ];

        #1
        $data[] = [
            $container = TokenContainer::factory('<?php include_once "foo.php"; ?>'),
            $container[1], // include
            Result::factory([$container[1], $container[2], $container[3], $container[4]]),
        ];

        #2
        $data[] = [
            $container = TokenContainer::factory('<?php require "foo.php"; ?>'),
            $container[1], // include
            Result::factory([$container[1], $container[2], $container[3], $container[4]]),
        ];

        #3
        $data[] = [
            $container = TokenContainer::factory('<?php require_once "foo.php"; ?>'),
            $container[1], // include
            Result::factory([$container[1], $container[2], $container[3], $container[4]]),
        ];

        return $data;
    }

    /**
     * @dataProvider findProvider
     *
     * @param TokenContainer $container
     * @param Token          $token
     * @param Result         $expectedResult
     *
     * @throws Exception
     */
    public function testFind(TokenContainer $container, Token $token, Result $expectedResult)
    {
        $finder       = new IncludeAndRequireFinder();
        $actualResult = $finder->find($token, $container);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }

    public function testFindThrowsExceptionIfStartTagIsNotIncludeOrRequire()
    {
        $container = TokenContainer::createEmptyContainer();
        $finder    = new IncludeAndRequireFinder();
        $token     = Token::createFromValueAndType('Foo', T_WHITESPACE);

        $this->setExpectedException(TokenFinderException::class, '', TokenFinderException::UNSUPPORTED_START_TOKEN);
        $finder->find($token, $container);
    }
}
