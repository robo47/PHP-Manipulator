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
}