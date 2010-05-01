<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use PHP\Manipulator\TokenFinder\Result;
use PHP\Manipulator\TokenFinder\FunctionFinder;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

/**
 * @group TokenFinder
 * @group TokenFinder\FunctionFinder
 */
class FunctionFinderTest
extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function findProvider()
    {
        $data = array();
        $path = '/TokenFinder/FunctionFinder/';

        #0 function
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input0.php'),
            $c[3],
            array(),
            $this->getResultFromContainer($c, 3, 14),
        );

        #1 Class method
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input1.php'),
            $c[11],
            array(),
            $this->getResultFromContainer($c, 11, 22),
        );

        #2 function with code and braces in it
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input2.php'),
            $c[3],
            array(),
            $this->getResultFromContainer($c, 3, 43),
        );

        #3 class method with code and braces in it
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input3.php'),
            $c[13],
            array(),
            $this->getResultFromContainer($c, 13, 53),
        );

        #4 abstract class method
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input4.php'),
            $c[13],
            array(),
            $this->getResultFromContainer($c, 13, 24),
        );

        #5 including prefixes
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input5.php'),
            $c[15],
            array('includeMethodProperties' => true),
            $this->getResultFromContainer($c, 11, 55),
        );

        #6 including phpdoc
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input6.php'),
            $c[15],
            array('includePhpdoc' => true),
            $this->getResultFromContainer($c, 9, 55),
        );

        #6 including phpdoc and methodproperties
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input7.php'),
            $c[15],
            array('includePhpdoc' => true, 'includeMethodProperties' => true),
            $this->getResultFromContainer($c, 9, 55),
        );

        #8 including phpdoc with some ugly comments inbetween
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input8.php'),
            $c[21],
            array('includePhpdoc' => true),
            $this->getResultFromContainer($c, 9, 61),
        );

        #9 include methodproperties finds methods without properties too
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input9.php'),
            $c[22],
            array('includeMethodProperties' => true),
            $this->getResultFromContainer($c, 22, 62),
        );

        #10 include methodproperties finds methods without properties too
        $data[] = array(
            $c = $this->getContainerFromFixture($path . 'input10.php'),
            $c[8],
            array('includeMethodProperties' => true),
            $this->getResultFromContainer($c, 8, 16),
        );

        // abstract functions / methods

        return $data;
    }

    /**
     * @dataProvider findProvider
     * @covers \PHP\Manipulator\TokenFinder\FunctionFinder::find
     * @covers \PHP\Manipulator\TokenFinder\FunctionFinder::<protected>
     */
    public function testFind($container, $token, $params, $expectedResult)
    {
        $finder = new FunctionFinder();
        $actualResult = $finder->find($token, $container, $params);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }
}