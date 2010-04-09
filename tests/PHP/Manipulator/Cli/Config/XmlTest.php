<?php

namespace Tests\PHP\Manipulator\Cli\Config;

use PHP\Manipulator\Cli\Config;
use PHP\Manipulator\Cli\Config\Xml as XmlConfig;

/**
 * @group Cli_Config
 * @group Cli_Config_Xml
 */
class XmlTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Cli\Config\Xml
     */
    public function testConfig()
    {
        $config = $this->getXmlConfig(0);

        $options = $config->getOptions();
        $this->assertType('array', $options);
        $this->assertCount(3, $options);

        $this->assertArrayHasKey('rulePrefix', $options);
        $this->assertEquals('\Baa\Foo\Rule\\', $options['rulePrefix']);
        $this->assertArrayHasKey('rulesetPrefix', $options);
        $this->assertEquals('\Baa\Foo\Ruleset\\', $options['rulesetPrefix']);
        $this->assertArrayHasKey('fileSuffix', $options);
        $this->assertEquals('.phtml', $options['fileSuffix']);

        $rules = $config->getRules();
        $this->assertType('array', $rules);
        $this->assertCount(6, $rules);

        $this->assertType('\Baa\Foo\Rule\FirstRule', $rules[0]);

        $this->assertEquals('foo', $rules[0]->getOption('baa'));

        $this->assertType('\Foo\Baa\Rule\SecondRule', $rules[1]);
        $this->assertEquals('baa', $rules[1]->getOption('foo'));
        $this->assertTrue($rules[1]->getOption('someTrueBoolean'));
        $this->assertFalse($rules[1]->getOption('someFalseBoolean'));

        $this->assertType('\Baa\Foo\Rule\ThirdRule', $rules[2]);
        $this->assertEquals('bla', $rules[2]->getOption('blub'));

        $this->assertType('\Baa\Foo\Rule\FourthRule', $rules[3]);
        $this->assertEquals('blub', $rules[3]->getOption('bla'));

        $this->assertType('\Foo\Baa\Rule\FifthRule', $rules[4]);
        $this->assertEquals('foo', $rules[4]->getOption('baa'));

        $this->assertType('\Foo\Baa\Rule\SixthsRule', $rules[5]);
        $this->assertEquals('baa', $rules[5]->getOption('foo'));


        $files = $config->getFiles();
        $this->assertType('array', $files);
        $this->assertCount(2, $files);

        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir0/Blub.phtml', $files);
        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir1/Baafoo.php', $files);
    }

    /**
     * @covers \PHP\Manipulator\Cli\Config\Xml::_castValue
     */
    public function testRuleOptionsCastWorks()
    {
        $config = $this->getXmlConfig(2);

        $rules = $config->getRules();
        $this->assertType('array', $rules);
        $this->assertCount(1, $rules);

        $rule = $rules[0];

        $this->assertType('\Baa\Foo\Rule\FirstRule', $rule);
        /* @var $rule \PHP\Manipulator\Rule */
        $this->assertCount(9, $rule->getOptions());

        $this->assertType('integer', $rule->getOption('integerOne'));
        $this->assertSame(1, $rule->getOption('integerOne'));

        $this->assertType('integer', $rule->getOption('integerTwenty'));
        $this->assertSame(20, $rule->getOption('integerTwenty'));

        $this->assertType('bool', $rule->getOption('booleanTrue'));
        $this->assertSame(true, $rule->getOption('booleanTrue'));

        $this->assertType('bool', $rule->getOption('booleanFalse'));
        $this->assertSame(false, $rule->getOption('booleanFalse'));

        $this->assertType('array', $rule->getOption('array'));
        $this->assertEquals(array("foo"), $rule->getOption('array'));

        $this->assertType('object', $rule->getOption('object'));
        $this->assertEquals((object)"foo", $rule->getOption('object'));

        $this->assertType('float', $rule->getOption('real'));
        $this->assertEquals(1.23, $rule->getOption('real'));

        $this->assertType('float', $rule->getOption('float'));
        $this->assertEquals(1.23, $rule->getOption('float'));

        $this->assertType('float', $rule->getOption('double'));
        $this->assertEquals(1.23, $rule->getOption('double'));
    }
}