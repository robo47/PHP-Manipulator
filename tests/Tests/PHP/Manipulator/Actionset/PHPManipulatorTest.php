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
        $actionset = new PHPManipulator();
        $actions = $actionset->getActions();
        $this->assertCount(9, $actions);
        $this->markTestIncomplete('Foo');
    }
}
