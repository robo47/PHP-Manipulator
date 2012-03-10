<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveLeadingAndTrailingEmptyLinesInPhpdoc;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\RemoveLeadingAndTrailingEmptyLinesInPhpdoc
 */
class RemoveLeadingAndTrailingEmptyLinesInPhpdocTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\RemoveLeadingAndTrailingEmptyLinesInPhpdoc::init
     */
    public function testConstructorDefaults()
    {
        $action = new RemoveLeadingAndTrailingEmptyLinesInPhpdoc();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveLeadingAndTrailingEmptyLinesInPhpdoc/';

        #0 Leading empty line
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1 Trailing
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2 Leading and Trailing
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        #3 Leading and Trailing and empty line in middle
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\RemoveLeadingAndTrailingEmptyLinesInPhpdoc
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new RemoveLeadingAndTrailingEmptyLinesInPhpdoc($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
