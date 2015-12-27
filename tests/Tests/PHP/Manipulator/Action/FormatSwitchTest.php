<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\FormatSwitch;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\FormatSwitch
 */
class FormatSwitchTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new FormatSwitch();

        $this->assertTrue($action->getOption(FormatSwitch::OPTION_SPACE_AFTER_SWITCH));
        $this->assertTrue($action->getOption(FormatSwitch::OPTION_SPACE_BEFORE_CURLY_BRACE));
        $this->assertFalse($action->getOption(FormatSwitch::OPTION_BREAK_BEFORE_CURLY_BRACE));

        $this->assertCount(3, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/FormatSwitch/';

        #0 Space after switch
        $data[] = [
            [FormatSwitch::OPTION_SPACE_AFTER_SWITCH => true, FormatSwitch::OPTION_SPACE_BEFORE_CURLY_BRACE => true],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        return $data;
    }

    /**
     * @param array          $options
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     *
     * @dataProvider actionProvider
     */
    public function testAction(array $options, TokenContainer $input, TokenContainer $expectedTokens)
    {
        $action = new FormatSwitch($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
