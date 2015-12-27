<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveComments;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveComments
 */
class RemoveCommentsTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveComments();
        $this->assertTrue(
            $action->getOption(RemoveComments::OPTION_REMOVE_DOC_COMMENTS),
            'Default value for removeDocComments is wrong'
        );
        $this->assertTrue(
            $action->getOption(RemoveComments::OPTION_REMOVE_STANDARD_COMMENTS),
            'Default value for removeStandardComments is wrong'
        );
        $this->assertCount(2, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/RemoveComments/';

        #0
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        #1
        $data[] = [
            ['removeDocComments' => false, 'removeStandardComments' => true],
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
        ];

        #2
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
        ];

        #3
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input3.php'),
            $this->getContainerFromFixture($path.'output3.php'),
        ];

        #4
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input4.php'),
            $this->getContainerFromFixture($path.'output4.php'),
        ];

        #5
        $data[] = [
            ['removeDocComments' => true, 'removeStandardComments' => false],
            $this->getContainerFromFixture($path.'input5.php'),
            $this->getContainerFromFixture($path.'output5.php'),
        ];

        #6
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input6.php'),
            $this->getContainerFromFixture($path.'output6.php'),
        ];

        #7
        $data[] = [
            [],
            $this->getContainerFromFixture($path.'input7.php'),
            $this->getContainerFromFixture($path.'output7.php'),
        ];

        #8 big real-life example
        $data[] = [
            ['removeDocComments' => true, 'removeStandardComments' => true],
            $this->getContainerFromFixture($path.'input8.php'),
            $this->getContainerFromFixture($path.'output8.php'),
        ];

        #9 check works with \r\n
        $data[] = [
            [],
            TokenContainer::factory("<?php\r\necho \$foo;// foo\r\necho \$baa;\r\n ?>"),
            TokenContainer::factory("<?php\r\necho \$foo;\r\necho \$baa;\r\n ?>"),
        ];

        #10 check works with \r
        $data[] = [
            [],
            TokenContainer::factory("<?php\recho \$foo;// foo\recho \$baa;\r ?>"),
            TokenContainer::factory("<?php\recho \$foo;\recho \$baa;\r ?>"),
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
        $action = new RemoveComments($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
