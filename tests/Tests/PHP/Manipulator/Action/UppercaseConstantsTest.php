<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\UppercaseConstants;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\UppercaseConstants
 */
class UppercaseConstantsTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\UppercaseConstants::init
     */
    public function testConstructorDefaults()
    {
        $action = new UppercaseConstants();
        $this->assertCount(0, $action->getOptions());
    }

    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();
        $path = '/Action/UppercaseConstants/';

        #0 Simple class-Constant and accessing it
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
            false
        );

        #1 Test it does not uppercase method-calls
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
            false
        );

        #2 Normal constant
        $data[] = array(
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
            false
        );

        #3 function-parameter
        $data[] = array(
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
            false
        );

        #4 method-parameter
        $data[] = array(
            $this->getContainerFromFixture($path . 'input4.php'),
            $this->getContainerFromFixture($path . 'output4.php'),
            false
        );

        #5 namespaces should not be uppercased (using namespace via curly braces)
        $data[] = array(
            $this->getContainerFromFixture($path . 'input5.php'),
            $this->getContainerFromFixture($path . 'output5.php'),
            false
        );

        #6 namespaces should not be uppercased
        $data[] = array(
            $this->getContainerFromFixture($path . 'input6.php'),
            $this->getContainerFromFixture($path . 'output6.php'),
            false
        );

        #7 use inside namespace (using namespace via curly braces)
        $data[] = array(
            $this->getContainerFromFixture($path . 'input7.php'),
            $this->getContainerFromFixture($path . 'output7.php'),
            false
        );

        #8 use inside namespace
        $data[] = array(
            $this->getContainerFromFixture($path . 'input8.php'),
            $this->getContainerFromFixture($path . 'output8.php'),
            false
        );

        #9 test WHITESPACE between someTokens does not make any problems
        $data[] = array(
            $this->getContainerFromFixture($path . 'input9.php'),
            $this->getContainerFromFixture($path . 'output9.php'),
            false
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\UppercaseConstants
     * @dataProvider manipulateProvider
     */
    public function testManipulate($container, $expectedContainer, $strict)
    {
        $manipulator = new UppercaseConstants();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}