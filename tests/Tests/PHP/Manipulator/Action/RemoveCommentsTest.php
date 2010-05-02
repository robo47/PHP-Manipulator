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
        $this->assertTrue($action->getOption('removeDocComments'), 'Default value for removeDocComments is wrong');
        $this->assertTrue($action->getOption('removeStandardComments'), 'Default value for removeStandardComments is wrong');
        $this->assertCount(2, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/RemoveComments/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1
        $data[] = array(
            array('removeDocComments' => false, 'removeStandardComments' => true),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        #3
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
        );

        #4
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input4.php'),
            $this->getContainerFromFixture($path . 'output4.php'),
        );

        #5
        $data[] = array(
            array('removeDocComments' => true, 'removeStandardComments' => false),
            $this->getContainerFromFixture($path . 'input5.php'),
            $this->getContainerFromFixture($path . 'output5.php'),
        );

        #6
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input6.php'),
            $this->getContainerFromFixture($path . 'output6.php'),
        );

        #7
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input7.php'),
            $this->getContainerFromFixture($path . 'output7.php'),
        );

        #8 big real-life example
        $data[] = array(
            array('removeDocComments' => true, 'removeStandardComments' => true),
            $this->getContainerFromFixture($path . 'input8.php'),
            $this->getContainerFromFixture($path . 'output8.php'),
        );

        #9 check works with \r\n
        $data[] = array(
            array(),
            new TokenContainer("<?php\r\necho \$foo;// foo\r\necho \$baa;\r\n ?>"),
            new TokenContainer("<?php\r\necho \$foo;\r\necho \$baa;\r\n ?>"),
        );

        #10 check works with \r
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