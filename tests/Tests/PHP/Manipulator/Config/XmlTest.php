<?php

namespace Tests\PHP\Manipulator\Config;

use PHP\Manipulator\Config;
use PHP\Manipulator\Config\Xml as XmlConfig;

/**
 * @group Cli\
 * @group Config
 * @group Config\Xml
 */
class XmlTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Config\Xml
     */
    public function testConfig()
    {
        $config = $this->getXmlConfig(0);

        $options = $config->getOptions();
        $this->assertType('array', $options);

        $this->assertArrayHasKey('rulePrefix', $options);
        $this->assertEquals('\Baa\Foo\Rule\\', $options['rulePrefix']);
        $this->assertArrayHasKey('rulesetPrefix', $options);
        $this->assertEquals('\Baa\Foo\Ruleset\\', $options['rulesetPrefix']);
        $this->assertArrayHasKey('fileSuffix', $options);
        $this->assertEquals('.phtml', $options['fileSuffix']);
        $this->assertArrayHasKey('defaultNewline', $options);
        $this->assertEquals("\n", $options['defaultNewline']);

        $this->assertCount(4, $options);

        $rules = $config->getRules();
        $this->assertType('array', $rules);

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

        $this->assertCount(6, $rules);

        $files = $config->getFiles();
        $this->assertType('array', $files);

        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir0/Blub.phtml', $files);
        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir1/Baafoo.php', $files);

        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir2/Baafoo.php', $files);
        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir2/Baa.php', $files);

        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir3/Baa.php', $files);

        $this->assertCount(5, $files);
    }

    /**
     * @covers \PHP\Manipulator\Config\Xml::_castValue
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
        $this->assertEquals((object) "foo", $rule->getOption('object'));

        $this->assertType('float', $rule->getOption('real'));
        $this->assertEquals(1.23, $rule->getOption('real'));

        $this->assertType('float', $rule->getOption('float'));
        $this->assertEquals(1.23, $rule->getOption('float'));

        $this->assertType('float', $rule->getOption('double'));
        $this->assertEquals(1.23, $rule->getOption('double'));

        $this->assertType('string', $rule->getOption('linebreaks'));
        $this->assertEquals("\n\r\n\r", $rule->getOption('linebreaks'));

        $this->assertCount(10, $rule->getOptions());
    }
}