<?php

/**
 * @group ContainerManipulator_SetWhitespaceBeforeToken
 */
class PHP_Formatter_ContainerManipulator_SetWhitespaceBeforeTokenTest extends PHPFormatterTestCase
{
    /**
     * @return array
     */
    public function manipulateProvider()
    {
        $data = array();

        $data = array();
        $path = '/ContainerManipulator/SetWhitespaceBeforeToken/';

        #0
        $data[] = array(
            $inputContainer = $this->getTokenArrayFromFixtureFile($path . 'input0'),
            $this->getTokenArrayFromFixtureFile($path . 'output0'),
            array(
                'tokens' => array($inputContainer[3]),
                'whitespace' => array(T_CONCAT_EQUAL => ' '),
            ),
            false
        );

        #1
        $data[] = array(
            $inputContainer = $this->getTokenArrayFromFixtureFile($path . 'input1'),
            $this->getTokenArrayFromFixtureFile($path . 'output1'),
            array(
                'tokens' => array($inputContainer[4]),
                'whitespace' => array(T_CONCAT_EQUAL => '  '),
            ),
            false
        );

        #2
        $data[] = array(
            $inputContainer = $this->getTokenArrayFromFixtureFile($path . 'input2'),
            $this->getTokenArrayFromFixtureFile($path . 'output2'),
            array(
                'tokens' => array($inputContainer[4]),
                'whitespace' => array(T_CONCAT_EQUAL => ''),
            ),
            false
        );

        return $data;
    }

    /**
     * @dataProvider manipulateProvider
     * @covers PHP_Formatter_ContainerManipulator_SetWhitespaceBeforeToken
     */
    public function testManipulate($container, $expectedContainer, $params, $strict)
    {
        $manipulator = new PHP_Formatter_ContainerManipulator_SetWhitespaceBeforeToken();
        $manipulator->manipulate($container, $params);
        $this->assertTokenContainerMatch($expectedContainer, $container, $strict);
    }
}