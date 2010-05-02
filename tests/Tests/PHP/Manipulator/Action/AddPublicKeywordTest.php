<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\AddPublicKeyword;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\AddPublicKeyword
 */
class AddPublicKeywordTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\AddPublicKeyword::init
     */
    public function testConstructorDefaults()
    {
        $action = new AddPublicKeyword();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/AddPublicKeyword/';

        #0
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1 Test it only adds to methods, not functions
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        // @todo test this
        #2 Test it only adds to methods and not to functions inside methods
        $data[] = array(
            array(),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );
//
//        // @todo test this
//        #3 Test it not adds to anonymous functions!
//        $data[] = array(
//            array(),
//            $this->getContainerFromFixture($path . 'input3.php'),
//            $this->getContainerFromFixture($path . 'output3.php'),
//        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\AddPublicKeyword
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new AddPublicKeyword($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}