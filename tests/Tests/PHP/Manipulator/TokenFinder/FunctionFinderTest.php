<?php

namespace Tests\PHP\Manipulator\TokenFinder;

use Exception;
use PHP\Manipulator\Exception\TokenFinderException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\FunctionFinder;
use PHP\Manipulator\TokenFinder\Result;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\TokenFinder\FunctionFinder
 */
class FunctionFinderTest extends TestCase
{
    /**
     * @return array
     */
    public function findProvider()
    {
        $data = [];
        $path = '/TokenFinder/FunctionFinder/';

        #0 function
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input0.php'),
            $c[3],
            [],
            $this->getResultFromContainer($c, 3, 14),
        ];

        #1 Class method
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input1.php'),
            $c[11],
            [],
            $this->getResultFromContainer($c, 11, 22),
        ];

        #2 function with code and braces in it
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input2.php'),
            $c[3],
            [],
            $this->getResultFromContainer($c, 3, 43),
        ];

        #3 class method with code and braces in it
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input3.php'),
            $c[13],
            [],
            $this->getResultFromContainer($c, 13, 53),
        ];

        #4 static class method
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input4.php'),
            $c[13],
            [],
            $this->getResultFromContainer($c, 13, 24),
        ];

        #5 including prefixes
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input5.php'),
            $c[15],
            ['includeMethodProperties' => true],
            $this->getResultFromContainer($c, 11, 55),
        ];

        #6 including phpdoc
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input6.php'),
            $c[15],
            ['includePhpdoc' => true],
            $this->getResultFromContainer($c, 9, 55),
        ];

        #6 including phpdoc and methodproperties
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input7.php'),
            $c[15],
            ['includePhpdoc' => true, 'includeMethodProperties' => true],
            $this->getResultFromContainer($c, 9, 55),
        ];

        #8 including phpdoc with some ugly comments inbetween
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input8.php'),
            $c[21],
            ['includePhpdoc' => true],
            $this->getResultFromContainer($c, 9, 61),
        ];

        #9 include methodproperties finds methods without properties too
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input9.php'),
            $c[22],
            ['includeMethodProperties' => true],
            $this->getResultFromContainer($c, 22, 62),
        ];

        #10 include methodproperties finds methods without properties too
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input10.php'),
            $c[8],
            ['includeMethodProperties' => true],
            $this->getResultFromContainer($c, 8, 16),
        ];

        #11 include phpdoc finds methods without phpdoc too
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input11.php'),
            $c[8],
            ['includeMethodProperties' => true],
            $this->getResultFromContainer($c, 8, 16),
        ];

        #12 abstract class method
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input12.php'),
            $c[14],
            ['includeMethodProperties' => true],
            $this->getResultFromContainer($c, 10, 22),
        ];

        #13 interface method
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input13.php'),
            $c[10],
            ['includeMethodProperties' => true],
            $this->getResultFromContainer($c, 8, 18),
        ];

        #14 try to include functionproperties but none exist
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input14.php'),
            $c[2],
            ['includeMethodProperties' => true],
            $this->getResultFromContainer($c, 2, 13),
        ];

        #15 try to include phpdoc but none exist
        $data[] = [
            $c = $this->getContainerFromFixture($path.'input15.php'),
            $c[2],
            ['includeMethodProperties' => true],
            $this->getResultFromContainer($c, 2, 13),
        ];

        return $data;
    }

    /**
     * @dataProvider findProvider
     *
     * @param TokenContainer $container
     * @param Token          $token
     * @param array          $params
     * @param Result         $expectedResult
     *
     * @throws Exception
     */
    public function testFind(TokenContainer $container, Token $token, array $params, Result $expectedResult)
    {
        $finder       = new FunctionFinder();
        $actualResult = $finder->find($token, $container, $params);
        $this->assertFinderResultsMatch($expectedResult, $actualResult);
    }

    public function testFinderThrowsExceptionIfFirstTokenIsNotTFunctionToken()
    {
        $container = $this->getContainerFromFixture('/TokenFinder/FunctionFinder/input0.php');
        $finder    = new FunctionFinder();

        $this->setExpectedException(TokenFinderException::class, '', TokenFinderException::UNSUPPORTED_START_TOKEN);
        $finder->find($container[0], $container);
    }
}
