<?php

namespace Tests\Mock;

use Tests\Mock\TokenFinderMock;
use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group Mock
 * @group Mock\TokenFinder
 */
class TokenFinderMockTest
extends \Tests\TestCase
{

    /**
     * @covers \Tests\Mock\TokenFinderMock::__construct
     */
    public function testConstruct()
    {
        $result = new Result();
        $mock = new TokenFinderMock($result);
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
     * @covers \Tests\Mock\TokenFinderMock::find
     * @covers \Tests\Mock\TokenFinderMock::<protected>
     */
    public function testFind($token, $params, $container, $expectedResult)
    {
        $finder = new TokenFinderMock($expectedResult);
        $actualResult = $finder->find($token, $container, $params);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }
}