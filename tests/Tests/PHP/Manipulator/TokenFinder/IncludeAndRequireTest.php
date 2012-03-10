<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder\IncludeAndRequire;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @gr
 * @group TokenFinder
 * @group TokenFinder\IncludeAndRequire
 */
class IncludeAndRequireTest
extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function findProvider()
    {
        $data = array();

        #0
        $data[] = array(
            $container = new TokenContainer('<?php include "foo.php"; ?>'),
            $container[1], // include
            array(),
            Result::factory(array($container[1], $container[2], $container[3], $container[4])),
        );

        #1
        $data[] = array(
            $container = new TokenContainer('<?php include_once "foo.php"; ?>'),
            $container[1], // include
            array(),
            Result::factory(array($container[1], $container[2], $container[3], $container[4])),
        );

        #2
        $data[] = array(
            $container = new TokenContainer('<?php require "foo.php"; ?>'),
            $container[1], // include
            array(),
            Result::factory(array($container[1], $container[2], $container[3], $container[4])),
        );

        #3
        $data[] = array(
            $container = new TokenContainer('<?php require_once "foo.php"; ?>'),
            $container[1], // include
            array(),
            Result::factory(array($container[1], $container[2], $container[3], $container[4])),
        );

        return $data;
    }

    /**
     * @dataProvider findProvider
     * @covers \PHP\Manipulator\TokenFinder\IncludeAndRequire::find
     * @covers \PHP\Manipulator\TokenFinder\IncludeAndRequire::<protected>
     */
    public function testFind($container, $token, $params, $expectedResult)
    {
        $finder = new IncludeAndRequire();
        $actualResult = $finder->find($token, $container, $params);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\IncludeAndRequire::find
     */
    public function testFindThrowsExceptionIfStartTagIsNotIncludeOrRequire()
    {
        $container = new TokenContainer();
        $finder = new IncludeAndRequire();
        $token = new Token('Foo', T_WHITESPACE);

        try {
            $finder->find($token, $container);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('Start-token is not one of T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE', $e->getMessage(), 'Wrong exception message');
        }
    }
}
