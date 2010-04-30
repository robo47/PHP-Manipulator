<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\StripNonPhp;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\StripNonPhp
 */
class StripNonPhpTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/StripNonPhp/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\StripNonPhp::run
     * @covers \PHP\Manipulator\Action\StripNonPhp::<protected>
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new StripNonPhp($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }

    /**
     * @return array
     */
    public function shortTagsOnlyactionProvider()
    {
        $data = array();
        $path = '/Action/StripNonPhp/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        return $data;
    }

    /**
     *
     * @covers \PHP\Manipulator\Action\StripNonPhp::run
     * @covers \PHP\Manipulator\Action\StripNonPhp::<protected>
     * @dataProvider shortTagsOnlyactionProvider
     */
    public function testActionWithShorttags($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $action = new StripNonPhp($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}