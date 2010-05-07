<?php

namespace Tests\PHP\Manipulator\Action;

use PHP\Manipulator\Action\FormatIfElseifElse;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @group Action\FormatIfElseifElse
 */
class FormatIfElseifElseTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Action\FormatIfElseifElse::init
     */
    public function testConstructorDefaults()
    {
        $action = new FormatIfElseifElse();
        $this->assertTrue($action->getOption('spaceAfterIf'), 'Default value for spaceAfterIf is wrong');
        $this->assertTrue($action->getOption('spaceAfterElseif'), 'Default value for spaceAfterElseif is wrong');
        $this->assertTrue($action->getOption('spaceAfterElse'), 'Default value for spaceAfterElse is wrong');

        $this->assertTrue($action->getOption('spaceBeforeIf'), 'Default value for spaceBeforeIf is wrong');
        $this->assertTrue($action->getOption('spaceBeforeElseif'), 'Default value for spaceBeforeElseif is wrong');
        $this->assertTrue($action->getOption('spaceBeforeElse'), 'Default value for spaceBeforeElse is wrong');

        $this->assertTrue($action->getOption('breakAfterCurlyBraceOfIf'), 'Default value for breakAfterCurlyBraceOfIf is wrong');
        $this->assertTrue($action->getOption('breakAfterCurlyBraceOfElse'), 'Default value for breakAfterCurlyBraceOfElse is wrong');
        $this->assertTrue($action->getOption('breakAfterCurlyBraceOfElseif'), 'Default value for breakAfterCurlyBraceOfElseif is wrong');

//        $this->assertTrue($action->getOption('breakBeforeCurlyBraceOfElse'), 'Default value for breakBeforeCurlyBraceOfElse is wrong');
//        $this->assertTrue($action->getOption('breakBeforeCurlyBraceOfElseif'), 'Default value for breakBeforeCurlyBraceOfElseif is wrong');

        $this->assertCount(9, $action->getOptions());
    }

    /**
     * @return array
     */
    public function actionProvider()
    {
        $data = array();
        $path = '/Action/FormatIfElseifElse/';

        #0 Test space after if is inserted
        $data[] = array(
            array('spaceAfterIf' => true, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input0.php'),
            $this->getContainerFromFixture($path . 'output0.php'),
        );

        #1Test if there is whitespace after if, it is replaced with one space if true === spaceAfterIf
        $data[] = array(
            array('spaceAfterIf' => true, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input1.php'),
            $this->getContainerFromFixture($path . 'output1.php'),
        );

        #2 Test if there is whitespace after if, it is deleted if false === spaceAfterIf
        $data[] = array(
            array('spaceAfterIf' => false, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input2.php'),
            $this->getContainerFromFixture($path . 'output2.php'),
        );

        #3 Test SpaceAfter And BeforeElse without existing space
        $data[] = array(
            array('spaceAfterElse' => true, 'spaceBeforeElse' => true, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input3.php'),
            $this->getContainerFromFixture($path . 'output3.php'),
        );

        #4 Test true SpaceAfter And BeforeElse with too much existing space
        $data[] = array(
            array('spaceAfterElse' => true, 'spaceBeforeElse' => true, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input4.php'),
            $this->getContainerFromFixture($path . 'output4.php'),
        );

        #5 Test false SpaceAfter And BeforeElse with existing space
        $data[] = array(
            array('spaceAfterElse' => false, 'spaceBeforeElse' => false, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input5.php'),
            $this->getContainerFromFixture($path . 'output5.php'),
        );

        #6 Test SpaceAfter And BeforeElse without existing space
        $data[] = array(
            array('spaceAfterElseif' => true, 'spaceBeforeElseif' => true, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input6.php'),
            $this->getContainerFromFixture($path . 'output6.php'),
        );

        #7 Test true SpaceAfter And BeforeElse with too much existing space
        $data[] = array(
            array('spaceAfterElseif' => true, 'spaceAfterElseif' => true, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input7.php'),
            $this->getContainerFromFixture($path . 'output7.php'),
        );

        #8 Test false SpaceAfter And BeforeElse with existing space
        $data[] = array(
            array('spaceAfterElseif' => false, 'spaceBeforeElseif' => false, 'breakAfterCurlyBraceOfIf' => false, 'breakAfterCurlyBraceOfElse' => false, 'breakAfterCurlyBraceOfElseif' => false, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input8.php'),
            $this->getContainerFromFixture($path . 'output8.php'),
        );

        #9 Test breaking works
        $data[] = array(
            array('breakAfterCurlyBraceOfIf' => true, 'breakAfterCurlyBraceOfElse' => true, 'breakAfterCurlyBraceOfElseif' => true, 'breakBeforeCurlyBraceOfElseAndElseIf' => false),
            $this->getContainerFromFixture($path . 'input9.php'),
            $this->getContainerFromFixture($path . 'output9.php'),
        );

//        #10 Test breaking works with nested if
//        $data[] = array(
//            array('breakAfterCurlyBraceOfIf' => true, 'breakAfterCurlyBraceOfElse' => true, 'breakAfterCurlyBraceOfElseif' => true, 'breakBeforeCurlyBraceOfElseAndElseIf' => true),
//            $this->getContainerFromFixture($path . 'input10.php'),
//            $this->getContainerFromFixture($path . 'output10.php'),
//        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\Action\FormatIfElseifElse
     * @dataProvider actionProvider
     */
    public function testAction($options, $input, $expectedTokens)
    {
        $action = new FormatIfElseifElse($options);
        $action->run($input);
        $this->assertTokenContainerMatch($expectedTokens, $input, false, 'Wrong output');
    }
}