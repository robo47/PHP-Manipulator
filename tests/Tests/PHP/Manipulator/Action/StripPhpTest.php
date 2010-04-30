<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\StripPhp;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\StripPhp
 */
class StripPhpTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\StripPhp::init
     */
    public function testConstructorDefaults()
    {
        $action = new StripPhp();
    }
    
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/StripPhp/';

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
     *
     * @covers \PHP\Manipulator\Action\StripPhp::run
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {

        $action = new StripPhp($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }

    /**
     * @return array
     */
    public function shortTagsOnlyactionProvider()
    {
        $data = array();
        $path = '/Action/StripPhp/';

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
     * @covers \PHP\Manipulator\Action\StripPhp::run
     * @covers \PHP\Manipulator\Action\StripPhp::<protected>
     * @dataProvider shortTagsOnlyactionProvider
     */
    public function testActionWithShorttags($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $action = new StripPhp($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}