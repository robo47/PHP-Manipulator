<?php

namespace Tests\PHP\Manipulator\Actionset;

use PHP\Manipulator\Actionset\PHPManipulator;

/**
 * @group Actionset
 * @group Actionset\PHPManipulator
 */
class PHPManipulatorTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Actionset\PHPManipulator::getActions
     */
    public function testEvaluate()
    {
        $Actionset = new PHPManipulator();
        $actions = $Actionset->getActions();
        $this->assertCount(4, $actions);
        $this->markTestIncomplete('Foo');
    }
}