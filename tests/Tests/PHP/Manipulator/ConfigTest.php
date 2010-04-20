<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Config;

/**
 * @group Config
 * @todo array-support for the file-suffixes ?
 */
class ConfigTest extends \Tests\TestCase
{

    /**
     * @covers \PHP\Manipulator\Config::__construct
     * @covers \PHP\Manipulator\Config::getOptions
     */
    public function testConstructorSetsDefaultOptions()
    {
        $config = $this->getConfig();

        $options = $config->getOptions();

        $this->assertCount(4, $options);

        $this->assertArrayHasKey('rulePrefix', $options);
        $this->assertEquals('\PHP\Manipulator\Rule\\', $options['rulePrefix']);

        $this->assertArrayHasKey('rulesetPrefix', $options);
        $this->assertEquals('\PHP\Manipulator\Ruleset\\', $options['rulesetPrefix']);

        $this->assertArrayHasKey('fileSuffix', $options);
        $this->assertEquals('.php', $options['fileSuffix']);

        $this->assertArrayHasKey('defaultNewline', $options);
        $this->assertEquals("\n", $options['defaultNewline']);
    }

    /**
     * @covers \PHP\Manipulator\Config::addFile
     * @covers \PHP\Manipulator\Config::getFiles
     */
    public function testAddFile()
    {
        $config = $this->getConfig();

        $this->assertCount(0, $config->getFiles());

        $fluent = $config->addFile('TestHelper.php');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $config->getFiles());
        $this->assertContains(\getcwd() . '/TestHelper.php', $config->getFiles());
    }

    /**
     * @covers \PHP\Manipulator\Config::addDirectory
     * @covers \PHP\Manipulator\Config::getFiles
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddDirectory()
    {
        $config = $this->getConfig();

        $this->assertCount(0, $config->getFiles());

        $fluent = $config->addDirectory('./_fixtures/Cli/Config/testDir0');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getFiles());
        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir0/Baa.php', $config->getFiles());
        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir0/Foo.php', $config->getFiles());
    }

    /**
     * @covers \PHP\Manipulator\Config::addIterator
     * @covers \PHP\Manipulator\Config::getFiles
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddIterator()
    {
        $config = $this->getConfig();

        $this->assertCount(0, $config->getFiles());

        $iterator = \File_Iterator_Factory::getFileIterator(\getcwd() .'/_fixtures/Cli/Config/testDir0', '.php');

        $fluent = $config->addIterator($iterator);


        $this->assertSame($config, $fluent, 'Does not provide fluent interface');

        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir0/Baa.php', $config->getFiles());
        $this->assertContains(\getcwd() . '/_fixtures/Cli/Config/testDir0/Foo.php', $config->getFiles());

        $this->assertCount(2, $config->getFiles());
    }

    /**
     * @covers \PHP\Manipulator\Config::addDirectory
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddDirectoryThrowsExceptionIfDirectoryWasNotFound()
    {
        $config = $this->getConfig();

        try {
            $config->addDirectory('/non/existing/path');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Unable to open path: /non/existing/path", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Config::addRuleset
     * @covers \PHP\Manipulator\Config::getRules
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddRuleset()
    {
        $config = $this->getConfig();
        $config->setOption('rulePrefix', '\Baa\Foo\Rule\\');
        $config->setOption('rulesetPrefix', '\Baa\Foo\Ruleset\\');

        $this->assertCount(0, $config->getRules());

        $fluent = $config->addRuleset('FirstRuleset');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getRules());

        $rules = $config->getRules();

        $this->assertType('\Baa\Foo\Rule\ThirdRule', $rules[0]);
        $this->assertType('\Baa\Foo\Rule\FourthRule', $rules[1]);

        $this->assertEquals('bla', $rules[0]->getOption('blub'));
        $this->assertEquals('blub', $rules[1]->getOption('bla'));
    }

    /**
     * @covers \PHP\Manipulator\Config::addRuleset
     * @covers \PHP\Manipulator\Config::getRules
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddRulesetWithPrefix()
    {
        $config = $this->getConfig();
        $config->setOption('rulePrefix', '\Baa\Foo\Rule\\');
        $config->setOption('rulesetPrefix', '\Baa\Foo\Ruleset\\');

        $this->assertCount(0, $config->getRules());

        $fluent = $config->addRuleset('FirstRuleset', '\Foo\Baa\Ruleset\\');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getRules());

        $rules = $config->getRules();

        $this->assertType('\Foo\Baa\Rule\ThirdRule', $rules[0]);
        $this->assertType('\Foo\Baa\Rule\FourthRule', $rules[1]);

        $this->assertEquals('bla', $rules[0]->getOption('blub'));
        $this->assertEquals('blub', $rules[1]->getOption('bla'));
    }

    /**
     * @covers \PHP\Manipulator\Config::addRule
     * @covers \PHP\Manipulator\Config::getRules
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddRule()
    {
        $config = $this->getConfig();
        $config->setOption('rulePrefix', '\Baa\Foo\Rule\\');

        $this->assertCount(0, $config->getRules());

        $fluent = $config->addRule('FirstRule');

        $rules = $config->getRules();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $rules);
        $this->assertType('\Baa\Foo\Rule\FirstRule', $rules[0]);
    }

    /**
     * @covers \PHP\Manipulator\Config::addRule
     * @covers \PHP\Manipulator\Config::getRules
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddRuleWithOptions()
    {
        $config = $this->getConfig();
        $config->setOption('rulePrefix', '\Baa\Foo\Rule\\');

        $this->assertCount(0, $config->getRules());

        $fluent = $config->addRule('FirstRule', null, array('baa' => 'foo', 'blub' => 'bla'));

        $rules = $config->getRules();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $rules);
        $this->assertType('\Baa\Foo\Rule\FirstRule', $rules[0]);

        $rule = $rules[0];
        /* @var $rule \Baa\Foo\Rule\FirstRule */
        $this->assertEquals('foo', $rule->getOption('baa'));
        $this->assertEquals('bla', $rule->getOption('blub'));
    }

    /**
     * @covers \PHP\Manipulator\Config::addRule
     * @covers \PHP\Manipulator\Config::getRules
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddRuleWithPrefix()
    {
        $config = $this->getConfig();
        $config->setOption('rulePrefix', '\Baa\Foo\Rule\\');

        $this->assertCount(0, $config->getRules());

        $fluent = $config->addRule('FirstRule', '\Foo\Baa\Rule\\');

        $rules = $config->getRules();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $rules);
        $this->assertType('\Foo\Baa\Rule\FirstRule', $rules[0]);
    }

    /**
     * @covers \PHP\Manipulator\Config::addRule
     * @covers \PHP\Manipulator\Config::getRules
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddRuleWithPrefixAndOptions()
    {
        $config = $this->getConfig();
        $config->setOption('rulePrefix', '\Baa\Foo\Rule\\');

        $this->assertCount(0, $config->getRules());

        $fluent = $config->addRule('FirstRule', '\Foo\Baa\Rule\\', array('baa' => 'foo', 'blub' => 'bla'));

        $rules = $config->getRules();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $rules);
        $this->assertType('\Foo\Baa\Rule\FirstRule', $rules[0]);

        $rule = $rules[0];
        /* @var $rule \Foo\Baa\Rule\FirstRule */
        $this->assertEquals('foo', $rule->getOption('baa'));
        $this->assertEquals('bla', $rule->getOption('blub'));
    }

    /**
     * @covers \PHP\Manipulator\Config::getFileContent
     */
    public function testGetFileContent()
    {
        $file = 'Cli/Config/config0.xml';
        $content = Config::getFileContent('_fixtures/' . $file);
        $this->assertEquals($this->getFixtureFileContent($file), $content);
    }

    /**
     * @covers \PHP\Manipulator\Config::getFileContent
     */
    public function testGetFileContentThrowExceptionOnNonExistingFile()
    {
        $file = '/non/existing/file.php';
        try {
            Config::getFileContent($file);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Unable to read file: /non/existing/file.php", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Config::getFileContent
     */
    public function testGetFileContentThrowExceptionIfPathReferencesADirectory()
    {
        $file = 'Cli/Config/';
        try {
            Config::getFileContent('_fixtures/' . $file);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Unable to read file: _fixtures/Cli/Config/", $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithXmlFromCode()
    {
        $config = Config::factory('xml', '<config></config>', false);
        $this->assertType('\PHP\Manipulator\Config\Xml', $config);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithNonAbstractFromCode()
    {
        $config = Config::factory('\Tests\PHP\Manipulator\Config\NonAbstract', '<config></config>', false);
        $this->assertType('\Tests\PHP\Manipulator\Config\NonAbstract', $config);
        $this->assertEquals('<config></config>', $config->data);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithXmlFromFile()
    {
        $file = 'Cli/Config/config0.xml';
        $config = Config::factory('xml', '_fixtures/' . $file, true);
        $this->assertType('\PHP\Manipulator\Config\Xml', $config);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithNonAbstractFromFile()
    {
        $file = 'Cli/Config/config0.xml';
        $config = Config::factory('\Tests\PHP\Manipulator\Config\NonAbstract', '_fixtures/' . $file, true);
        $this->assertType('\Tests\PHP\Manipulator\Config\NonAbstract', $config);
        $this->assertEquals($this->getFixtureFileContent($file), $config->data);
    }
}