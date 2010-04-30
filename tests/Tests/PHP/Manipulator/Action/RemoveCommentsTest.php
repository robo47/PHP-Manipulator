<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveComments;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\RemoveComments
 */
class RemoveCommentsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\RemoveComments::init
     */
    public function testConstructorDefaults()
    {
        $action = new RemoveComments();
        $this->assertTrue($action->getOption('removeDocComments'), 'Wrong default Option value for removeDocComments');
        $this->assertTrue($action->getOption('removeStandardComments'), 'Wrong default Option value for removeStandardComments');
    }
    
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveComments/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0'),
            $this->getContainerFromFixture($path . 'output0'),
        );

        #1
        $data[] = array(
            array('removeDocComments' => false, 'removeStandardComments' => true),
            $this->getContainerFromFixture($path . 'input1'),
            $this->getContainerFromFixture($path . 'output1'),
        );

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        #3
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input3'),
            $this->getContainerFromFixture($path . 'output3'),
        );

        #4
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input4'),
            $this->getContainerFromFixture($path . 'output4'),
        );

        #5
        $data[] = array(
            array('removeDocComments' => true, 'removeStandardComments' => false),
            $this->getContainerFromFixture($path . 'input5'),
            $this->getContainerFromFixture($path . 'output5'),
        );

        #6
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input6'),
            $this->getContainerFromFixture($path . 'output6'),
        );

        #7
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input7'),
            $this->getContainerFromFixture($path . 'output7'),
        );

        #8 big real-life example
        $data[] = array(
            array('removeDocComments' => true, 'removeStandardComments' => true),
            $this->getContainerFromFixture($path . 'input8'),
            $this->getContainerFromFixture($path . 'output8'),
        );

        #9 check works with \r\n
        $data[] = array(
            array(),
            new TokenContainer("<?php\r\necho \$foo;// foo\r\necho \$baa;\r\n ?>"),
            new TokenContainer("<?php\r\necho \$foo;\r\necho \$baa;\r\n ?>"),
        );

        #1 check works with \r
        $data[] = array(
            array(),
            new TokenContainer("<?php\recho \$foo;// foo\recho \$baa;\r ?>"),
            new TokenContainer("<?php\recho \$foo;\recho \$baa;\r ?>"),
        );

        return $data;
    }

    /**
     * @dataProvider actionProvider
     * @covers \PHP\Manipulator\Action\RemoveComments
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new RemoveComments($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}