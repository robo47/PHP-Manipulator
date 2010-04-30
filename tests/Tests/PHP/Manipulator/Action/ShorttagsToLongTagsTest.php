<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ShorttagsToLongTags;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\ShorttagsToLongTags
 */
class ShorttagsToLongTagsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\ShorttagsToLongTags::init
     */
    public function testConstructorDefaults()
    {
        $action = new ShorttagsToLongTags();
    }

    /**
     *
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ShorttagsToLongTags/';

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

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2'),
            $this->getContainerFromFixture($path . 'output2'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ShorttagsToLongTags::run
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $action = new ShorttagsToLongTags($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}