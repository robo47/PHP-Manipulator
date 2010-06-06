<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\FormatSwitch;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\FormatSwitch
 */
class FormatSwitchTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\FormatSwitch::init
     */
    public function testConstructorDefaults()
    {
        $action = new FormatSwitch();

        $this->assertTrue($action->getOption('spaceAfterSwitch'));
        $this->assertTrue($action->getOption('spaceAfterSwitchVariable'));
        $this->assertFalse($action->getOption('breakBeforeCurlyBrace'));

        $this->assertCount(3, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/FormatSwitch/';

        #0 Space after switch
        $data[] = array(
            array('spaceAfterSwitch' => true, 'spaceAfterSwitchVariable' => true),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\FormatSwitch
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new FormatSwitch($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}