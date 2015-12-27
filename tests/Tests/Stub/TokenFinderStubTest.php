<?php

namespace Tests\Stub;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\Result;
use Tests\TestCase;

/**
 * @covers Tests\Stub\TokenFinderStub
 */
class TokenFinderStubTest extends TestCase
{
    public function testConstruct()
    {
        $result = new Result();
        $stub   = new TokenFinderStub($result);
        $this->assertSame($result, $stub->result);
    }

    /**
     * @return array
     */
    public function findProvider()
    {
        $data = [];

        #0
        $data[] = [
            Token::createFromValue('Foo'),
            TokenContainer::createEmptyContainer(),
            new Result(),
        ];

        return $data;
    }

    /**
     * @dataProvider findProvider
     *
     * @param Token          $token
     * @param TokenContainer $container
     * @param Result         $expectedResult
     */
    public function testFind(Token $token, TokenContainer $container, Result $expectedResult)
    {
        $finder       = new TokenFinderStub($expectedResult);
        $actualResult = $finder->find($token, $container);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }
}
