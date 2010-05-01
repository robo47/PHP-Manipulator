<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\StripUnneededPhpCloseTag;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\StripUnneededPhpCloseTag
 */
class StripUnneededPhpCloseTagTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/Action/StripUnneededPhpCloseTag/';

        #0 Strip the ? > including whitespace before it
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            array(),
            false
        );

        #1 include all whitespace AFTER it too
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
            array(),
            false
        );

        #2 strip whitespace
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
            array('stripWhitespaceFromEnd' => true),
            false
        );

        #3 strip whitespace
        $data[] = array(
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
            array('stripWhitespaceFromEnd' => true),
            false
        );

        #2 strip whitespace
        $data[] = array(
            $this->getContainerFromFixture($path . 'input4.php'),
            $this->getContainerFromFixture($path . 'output4.php'),
            array('stripWhitespaceFromEnd' => false),
            false
        );

        #2 strip whitespace
        $data[] = array(
            $this->getContainerFromFixture($path . 'input5.php'),
            $this->getContainerFromFixture($path . 'output5.php'),
            array('stripWhitespaceFromEnd' => true),
            false
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\StripUnneededPhpCloseTag
     * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $options, $strict)
    {
        $manipulator = new StripUnneededPhpCloseTag($options);
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}