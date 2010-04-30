<?php

namespace Tests\PHP\Manipulator\ContainerConstraint;

use PHP\Manipulator\ContainerConstraint\ContainsClass;

/**
 * @group ContainerConstraint
 * @group ContainerConstraint\ContainsClass
 */
class ContainsClassTest extends \Tests\TestCase
{

    /**
     * @return array
     */
    public function evaluateProvider()
    {
        $data = array();
        $path = '/ContainerConstraint/ContainsClass/';

        #0
        $data[] = array(
            $this->getContainerFromFixture($path . 'input0.php'),
            true,
        );

        #1
        $data[] = array(
            $this->getContainerFromFixture($path . 'input1.php'),
            false,
        );

        return $data;
    }

    /**
     * @covers \PHP\Manipulator\ContainerConstraint\ContainsClass::evaluate
     * @dataProvider evaluateProvider
     */
    public function testContainerConstraint($input, $expectedResult)
    {
        $constraint = new ContainsClass();
        $this->assertSame($expectedResult, $constraint->evaluate($input));
    }
}