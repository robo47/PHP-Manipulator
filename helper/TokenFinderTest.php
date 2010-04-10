<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder\__classname__;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group TokenFinder___classname__
 */
class __classname__Test
extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function findProvider()
    {
        $data = array();
        $path = '/TokenFinder/__classname__/';

        #0
        $data[] = array(
            $container = $this->getContainerFromFixture($path . 'input0'),
            $container[1],
            array(),
            Result::factory(array($container[1], $container[2], $container[3], $container[4])),
        );

        return $data;
    }

    /**
     * @dataProvider findProvider
     * @covers \PHP\Manipulator\TokenFinder\__classname__::find
     * @covers \PHP\Manipulator\TokenFinder\__classname__::<protected>
     */
    public function testFind($container, $token, $params, $expectedResult)
    {
        $finder = new __classname__();
        $actualResult = $finder->find($token, $container, $params);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }
}