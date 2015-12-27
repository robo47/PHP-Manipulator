<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\UppercaseConstants;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\UppercaseConstants
 */
class UppercaseConstantsTest extends TestCase
{
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
        $data = [];

        $data['Simple class-Constant and accessing it']                                 = 0;
        $data['Test it does not uppercase method-calls']                                = 1;
        $data['Normal constant']                                                        = 2;
        $data['function-parameter']                                                     = 3;
        $data['method-parameter']                                                       = 4;
        $data['namespaces should not be uppercased (using namespace via curly braces)'] = 5;
        $data['namespaces should not be uppercased']                                    = 6;
        $data['use inside namespace (using namespace via curly braces)']                = 7;
        $data['use inside namespace']                                                   = 8;
        $data['Test WHITESPACE between someTokens does not make any problems']          = 9;

        return $this->convertContainerFixtureToProviderData($data, '/Action/UppercaseConstants/');
    }

    /**
     * @dataProvider manipulateProvider
     *
     * @param TokenContainer $container
     * @param TokenContainer $expectedContainer
     */
    public function testManipulate(TokenContainer $container, TokenContainer $expectedContainer)
    {
        $manipulator = new UppercaseConstants();
        $manipulator->run($container);
        $this->assertTokenContainerMatch($expectedContainer, $container);
    }
}
