<?php

namespace Tests\Stub;

use Tests\Stub\TokenFinderStub;
use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group Stub
 * @group Stub\TokenFinder
 */
class TokenFinderStubTest
extends \Tests\TestCase
{

    /**
     * @covers \Tests\Stub\TokenFinderStub::__construct
     */
    public function testConstruct()
    {
        $result = new Result();
        $stub = new TokenFinderStub($result);
        $this->assertSame($result, $stub->result);
    }

    /**
     * @return array
     */
    public function findProvider()
    {
        $data = array();
        $path = '/TokenFinder/Stub/';

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
     * @covers \Tests\Stub\TokenFinderStub::find
     * @covers \Tests\Stub\TokenFinderStub::<protected>
     */
    public function testFind($token, $params, $container, $expectedResult)
    {
        $finder = new TokenFinderStub($expectedResult);
        $actualResult = $finder->find($token, $container, $params);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }
}
