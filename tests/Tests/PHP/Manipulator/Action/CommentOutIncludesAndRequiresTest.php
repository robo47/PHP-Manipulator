<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\CommentOutIncludesAndRequires;
use PHP\Manipulator\TokenContainer;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Action\CommentOutIncludesAndRequires
 */
class CommentOutIncludesAndRequiresTest extends TestCase
{
    public function testConstructorDefaults()
    {
        $action = new CommentOutIncludesAndRequires();
        $this->assertTrue(
            $action->getOption(CommentOutIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY),
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
        $path = '/Action/CommentOutIncludesAndRequires/';

        #0
        $data[] = [
            [CommentOutIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY => false],
            $this->getContainerFromFixture($path.'input0.php'),
            $this->getContainerFromFixture($path.'output0.php'),
        ];

        #1
        $data[] = [
            [CommentOutIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY => true],
            $this->getContainerFromFixture($path.'input1.php'),
            $this->getContainerFromFixture($path.'output1.php'),
        ];

        #2
        $data[] = [
            [CommentOutIncludesAndRequires::OPTION_GLOBAL_SCOPE_ONLY => true],
            $this->getContainerFromFixture($path.'input2.php'),
            $this->getContainerFromFixture($path.'output2.php'),
        ];

        return $data;
    }

    /**
     * @dataProvider actionProvider
     *
     * @param array          $options
     * @param TokenContainer $input
     * @param TokenContainer $expectedTokens
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new CommentOutIncludesAndRequires($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}
