<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\ShorttagsToLongtags;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action
 * @group Action\ShorttagsToLongtags
 */
class ShorttagsToLongtagsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\ShorttagsToLongtags::init
     */
    public function testConstructorDefaults()
    {
        $action = new ShorttagsToLongtags();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/ShorttagsToLongtags/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\ShorttagsToLongtags::run
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $this->checkShorttags();

        $action = new ShorttagsToLongtags($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}