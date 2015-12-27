<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\RemoveIncludesAndRequires;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\RemoveIncludesAndRequires
 */
class RemoveIncludesAndRequiresTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new RemoveIncludesAndRequires();
        $this->assertTrue(
            $action->getOption(RemoveIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY),
            'Default value for globalScopeOnly is wrong'
        );
        $this->assertCount(1, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = [];
        $path = '/Action/RemoveIncludesAndRequires/';

        #0
        $data[] = [
            [RemoveIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY => false],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        #1
        $data[] = [
            [RemoveIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY => true],
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
        ];

        #2
        $data[] = [
            [RemoveIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY => true],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
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
        $action = new RemoveIncludesAndRequires($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
