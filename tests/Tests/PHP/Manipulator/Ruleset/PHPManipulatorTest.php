<?php

namespace Tests\PHP\Manipulator\Ruleset;

use PHP\Manipulator\Ruleset\PHPManipulator;

/**
 * @group Ruleset
 * @group Ruleset\PHPManipulator
 */
class PHPManipulatorTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Ruleset\PHPManipulator::getRules
     */
    public function testEvaluate()
    {
        $ruleset = new PHPManipulator();
        $rules = $ruleset->getRules();
        $this->assertCount(3, $rules);
        $this->markTestIncomplete('Foo');
    }
}