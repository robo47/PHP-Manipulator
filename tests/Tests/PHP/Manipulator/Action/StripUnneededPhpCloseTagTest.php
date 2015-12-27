<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\StripUnneededPhpCloseTag;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\StripUnneededPhpCloseTag
 */
class StripUnneededPhpCloseTagTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new StripUnneededPhpCloseTag();
        $this->assertFalse(
            $action->getOption(StripUnneededPhpCloseTag::OPTION_STRIP_WHITESPACE_FROM_END),
            'Default value for stripWhitespaceFromEnd is wrong'
        );
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/StripUnneededPhpCloseTag/';

        #0 Strip the ? > including whitespace before it
        $data[] = [
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
            [],
        ];

        #1 include all whitespace AFTER it too
        $data[] = [
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
            [],
        ];

        #2 strip whitespace
        $data[] = [
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
            [StripUnneededPhpCloseTag::OPTION_STRIP_WHITESPACE_FROM_END => true],
        ];

        #3 strip whitespace
        $data[] = [
            $this->getContainerFromFixture($path.'input3.php'),
            $this->getContainerFromFixture($path.'output3.php'),
            [StripUnneededPhpCloseTag::OPTION_STRIP_WHITESPACE_FROM_END => true],
        ];

        #2 strip whitespace
        $data[] = [
            $this->getContainerFromFixture($path.'input4.php'),
            $this->getContainerFromFixture($path.'output4.php'),
            [StripUnneededPhpCloseTag::OPTION_STRIP_WHITESPACE_FROM_END => false],
        ];

        #2 strip whitespace
        $data[] = [
            $this->getContainerFromFixture($path.'input5.php'),
            $this->getContainerFromFixture($path.'output5.php'),
            [StripUnneededPhpCloseTag::OPTION_STRIP_WHITESPACE_FROM_END => true],
        ];

        return $data;
    }

    /**
     * @dataProvider actionProvider
     *
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     * @param array          $options
     */
    public function testAction(TokenContainer $container, TokenContainer $expectedContainer, $options)
    {
        $manipulator = new StripUnneededPhpCloseTag($options);
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container);
    }
}
