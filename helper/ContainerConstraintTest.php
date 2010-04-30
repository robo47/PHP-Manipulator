<?php

namespace Tests\PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint\__classname__;

/**
 * @group ContainerConstraint\__classname__
 */
class __classname__Test extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();
        $path = '/__path__/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            true,
        );

        return $data;
    }

    /**
     * @covers __completeclassname__
     * @dataProvider evaluateProvider
     */
    public function testContainerConstraint($input, $expectedResult)
    {
        $constraint = new __classname__();
        $this->assertSame($expectedResult, $constraint->evaluate($input));
    }
}