<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder\Mock;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group TokenFinder_Mock
 */
class MockTest
extends \Tests\TestCase
{
    /**
     * @covers \PHP\Manipulator\TokenFinder\Mock::__construct
     */
    public function testConstruct()
    {
        $result = new Result();
        $mock = new Mock($result);
        $this->assertSame($result, $mock->result);
    }

    /**
     * @return array
     */
    public function findProvider()
    {
        $data = array();
        $path = '/TokenFinder/Mock/';

        #0
        $data[] = array(
            new Token('Foo'),
            null,
            new TokenContainer(),
            new Result()
        );

        return $data;
    }

    /**
     * @dataProvider findProvider
     * @covers \PHP\Manipulator\TokenFinder\Mock::find
     * @covers \PHP\Manipulator\TokenFinder\Mock::<protected>
     */
    public function testFind($token, $params, $container, $expectedResult)
    {
        $finder = new Mock($expectedResult);
        $actualResult = $finder->find($token, $container, $params);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }
}