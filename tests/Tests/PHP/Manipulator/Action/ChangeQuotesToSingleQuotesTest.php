<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ChangeQuotesToSingleQuotes;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\ChangeQuotesToSingleQuotes
 */
class ChangeQuotesToSingleQuotesTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\ChangeQuotesToSingleQuotes::init
     */
    public function testConstructorDefaults()
    {
        $action = new ChangeQuotesToSingleQuotes();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ChangeQuotesToSingleQuotes/';

        #0 simple string
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1 array-index
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2 Test string containing variables is not "destroyed"
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        #3 Test Strings containing linebreaks are not changed
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ChangeQuotesToSingleQuotes
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new ChangeQuotesToSingleQuotes($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
