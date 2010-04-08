<?php

namespace Test\PHP\Manipulator;

use PHP\Manipulator\IRuleset;

class IRulesetTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\IRuleset
     */
    public function testContainer()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\IRuleset');
        $this->assertTrue($reflection->isInterface(), 'Interface seems to not be an interface ? WTF!');
        $methods = $reflection->getMethods();
        $this->assertCount(1, $methods, 'Interface has wrong number of methods');
        $getRulesMethod = $methods[0];
        /* @var $getRulesMethod ReflectionMethod */
        $this->assertSame('getRules', $getRulesMethod->getName(), 'Method has wrong name');
        $this->assertSame(0, $getRulesMethod->getNumberOfParameters(), 'Method has wrong number of parameters');
    }
}