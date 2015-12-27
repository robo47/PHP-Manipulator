<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use Exception;
use PHP\Manipulator\Exception\TokenFinderException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder\SwitchFinder;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenFinder\SwitchFinder
 */
class SwitchFinderTest extends TestCase
{
    /**
     * @return array
     */
    public function findProvider()
    {
        $data = [];
        $path = '/TokenFinder/SwitchFinder/';

        #0
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input0.php'),
            $c[2],
            $this->getResultFromContainer($c, 2, 60),
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
        $finder       = new SwitchFinder();
        $actualResult = $finder->find($token, $container);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }

    public function testFinderThrowsExceptionIfFirstTokenIsNotTSwitchToken()
    {
        $container = $this->getContainerFromFixture('/TokenFinder/SwitchFinder/input0.php');
        $finder    = new SwitchFinder();
        $this->setExpectedException(TokenFinderException::class, '', TokenFinderException::UNSUPPORTED_START_TOKEN);
        $finder->find($container[0], $container);
    }
}
