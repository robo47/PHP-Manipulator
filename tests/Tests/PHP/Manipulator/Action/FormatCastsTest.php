<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\FormatCasts;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\FormatCasts
 */
class FormatCastsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\FormatCasts::init
     */
    public function testConstructorDefaults()
    {
        $searchedTokens = array(
            T_INT_CAST => '(int)',
            T_BOOL_CAST => '(bool)',
            T_DOUBLE_CAST => '(double)',
            T_OBJECT_CAST => '(object)',
            T_STRING_CAST => '(string)',
            T_UNSET_CAST => '(unset)',
            T_ARRAY_CAST => '(array)',
        );
        $action = new FormatCasts();
        $this->assertEquals($searchedTokens, $action->getOption('searchedTokens'));
        $this->assertEquals('', $action->getOption('whitespaceBehindCasts'));
        $this->assertCount(2, $action->getOptions());
    }

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/Action/FormatCasts/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            array(
                'searchedTokens' => array(
                    T_INT_CAST => '(iNt)',
                    T_BOOL_CAST => '(bOoL)',
                    T_DOUBLE_CAST => '(dOuBlE)',
                    T_OBJECT_CAST => '(oBjEcT)',
                    T_STRING_CAST => '(sTrInG)',
                    T_UNSET_CAST => '(uNsEt)',
                    T_ARRAY_CAST => '(aRrAy)',
                )
            ),
            true
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
            array(),
            true
        );

        #2 Test whitespace is created if wanted
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
            array('whitespaceBehindCasts' => ' '),
            false
        );

        #3 Test whitespace gets removed if not wanted
        $data[] = array(
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
            array('whitespaceBehindCasts' => ''),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers \PHP\Manipulator\Action\FormatCasts::run
     * @covers \PHP\Manipulator\Action\FormatCasts::<protected>
     */
    public function testManipulate($container, $expectedContainer, $options, $strict)
    {
        $manipulator = new FormatCasts($options);
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}
