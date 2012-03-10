<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder\SwitchFinder;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group TokenFinder\SwitchFinder
 */
class SwitchFinderTest
extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function findProvider()
    {
        $data = array();
        $path = '/TokenFinder/SwitchFinder/';

        #0
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input0.php'),
            $c[2],
            $this->getResultFromContainer($c, 2, 60),
        );

        return $data;
    }

    /**
     * @dataProvider findProvider
     * @covers \PHP\Manipulator\TokenFinder\SwitchFinder::find
     * @covers \PHP\Manipulator\TokenFinder\SwitchFinder::<protected>
     */
    public function testFind($container, $token, $expectedResult)
    {
        $finder = new SwitchFinder();
        $actualResult = $finder->find($token, $container);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }

    /**
     * @covers \PHP\Manipulator\TokenFinder\SwitchFinder::find
     */
    public function testFinderThrowsExceptionIfFirstTokenIsNotT_SWITCH()
    {
        $container = $this->getContainerFromFixture('/TokenFinder/SwitchFinder/input0.php');
        $finder = new SwitchFinder();
        try {
            $finder->find($container[0], $container);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('Starttoken is not T_SWITCH', $e->getMessage(), 'Wrong exception message');
        }
    }
}
