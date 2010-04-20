<?php

namespace Test\PHP\Manipulator;

use PHP\Manipulator\Ruleset;

/**
 * @group Ruleset
 */
class RulesetTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Ruleset
     */
    public function testContainer()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\Ruleset');
        $this->assertTrue($reflection->isAbstract(), 'Class must be abstract');

        $getRulesMethod = $reflection->getMethod('getRules');
        /* @var $getRulesMethod ReflectionMethod */
        $this->assertSame(0, $getRulesMethod->getNumberOfParameters(), 'Method has wrong number of parameters');

        $constructor = $reflection->getMethod('__construct');
        /* @var $constructor ReflectionMethod */
        $this->assertSame(1, $constructor->getNumberOfParameters(), 'Method has wrong number of parameters');
        $params = $constructor->getParameters();
        $optionsParameter = $params[0];
        /* @var $optionsParameter \ReflectionParameter */
        $this->assertTrue($optionsParameter->isArray(), 'Parameter has no array type hint');
        $this->assertEquals(array(), $optionsParameter->getDefaultValue(), 'Parameter was wrong default value');
    }
}