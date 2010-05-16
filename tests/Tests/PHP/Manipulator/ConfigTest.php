<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Config;
use Symfony\Components\Finder\Finder;
use Tests\Mock\ConfigMock;

/**
 * @group Config
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
       
        $this->assertArrayHasKey('actionPrefix', $options);
        $this->assertEquals('\PHP\Manipulator\Action\\', $options['actionPrefix']);

        $this->assertArrayHasKey('actionsetPrefix', $options);
        $this->assertEquals('\PHP\Manipulator\Actionset\\', $options['actionsetPrefix']);

        $this->assertArrayHasKey('fileSuffix', $options);
        $this->assertEquals('.php', $options['fileSuffix']);

        $this->assertArrayHasKey('defaultNewline', $options);
        $this->assertEquals("\n", $options['defaultNewline']);

        $this->assertCount(4, $options);
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

        $fluent = $config->addDirectory('./_fixtures/Config/testDir0');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getFiles());
        $this->assertContains(\getcwd() . '/_fixtures/Config/testDir0/Baa.php', $config->getFiles());
        $this->assertContains(\getcwd() . '/_fixtures/Config/testDir0/Foo.php', $config->getFiles());
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

        $finder = new Finder();
        $iterator = $finder->files()->name('*.php')->in(\getcwd() . '/_fixtures/Config/testDir0');

        $fluent = $config->addIterator($iterator->getIterator());


        $this->assertSame($config, $fluent, 'Does not provide fluent interface');

        $this->assertContains(\getcwd() . '/_fixtures/Config/testDir0/Baa.php', $config->getFiles());
        $this->assertContains(\getcwd() . '/_fixtures/Config/testDir0/Foo.php', $config->getFiles());

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
     * @covers \PHP\Manipulator\Config::addActionset
     * @covers \PHP\Manipulator\Config::getActions
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddActionset()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', '\Baa\Foo\Action\\');
        $config->setOption('actionsetPrefix', '\Baa\Foo\Actionset\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addActionset('FirstActionset');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getActions());

        $actions = $config->getActions();

        $this->assertType('\Baa\Foo\Action\ThirdAction', $actions[0]);
        $this->assertType('\Baa\Foo\Action\FourthAction', $actions[1]);

        $this->assertEquals('bla', $actions[0]->getOption('blub'));
        $this->assertEquals('blub', $actions[1]->getOption('bla'));
    }

    /**
     * @covers \PHP\Manipulator\Config::addActionset
     * @covers \PHP\Manipulator\Config::getActions
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddActionsetWithPrefix()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', '\Baa\Foo\Action\\');
        $config->setOption('actionsetPrefix', '\Baa\Foo\Actionset\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addActionset('FirstActionset', '\Foo\Baa\Actionset\\');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getActions());

        $actions = $config->getActions();

        $this->assertType('\Foo\Baa\Action\ThirdAction', $actions[0]);
        $this->assertType('\Foo\Baa\Action\FourthAction', $actions[1]);

        $this->assertEquals('bla', $actions[0]->getOption('blub'));
        $this->assertEquals('blub', $actions[1]->getOption('bla'));
    }

    /**
     * @covers \PHP\Manipulator\Config::addAction
     * @covers \PHP\Manipulator\Config::getActions
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddAction()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', '\Baa\Foo\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction');

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertType('\Baa\Foo\Action\FirstAction', $actions[0]);
    }

    /**
     * @covers \PHP\Manipulator\Config::addAction
     * @covers \PHP\Manipulator\Config::getActions
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddActionWithOptions()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', '\Baa\Foo\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', null, array('baa' => 'foo', 'blub' => 'bla'));

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertType('\Baa\Foo\Action\FirstAction', $actions[0]);

        $action = $actions[0];
        /* @var $action \Baa\Foo\Action\FirstAction */
        $this->assertEquals('foo', $action->getOption('baa'));
        $this->assertEquals('bla', $action->getOption('blub'));
    }

    /**
     * @covers \PHP\Manipulator\Config::addAction
     * @covers \PHP\Manipulator\Config::getActions
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddActionWithPrefix()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', '\Baa\Foo\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', '\Foo\Baa\Action\\');

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertType('\Foo\Baa\Action\FirstAction', $actions[0]);
    }

    /**
     * @covers \PHP\Manipulator\Config::addAction
     * @covers \PHP\Manipulator\Config::getActions
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddActionWithPrefixAndOptions()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', '\Baa\Foo\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', '\Foo\Baa\Action\\', array('baa' => 'foo', 'blub' => 'bla'));

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertType('\Foo\Baa\Action\FirstAction', $actions[0]);

        $action = $actions[0];
        /* @var $action \Foo\Baa\Action\FirstAction */
        $this->assertEquals('foo', $action->getOption('baa'));
        $this->assertEquals('bla', $action->getOption('blub'));
    }

    /**
     * @covers \PHP\Manipulator\Config::getFileContent
     */
    public function testGetFileContent()
    {
        $file = 'Config/config0.xml';
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
        $file = 'Config/';
        try {
            Config::getFileContent('_fixtures/' . $file);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Unable to read file: _fixtures/Config/", $e->getMessage(), 'Wrong exception message');
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
    public function testFactoryWithConfigMockFromCode()
    {
        $config = Config::factory('\Tests\Mock\ConfigMock', '<config></config>', false);
        $this->assertType('\Tests\Mock\ConfigMock', $config);
        $this->assertEquals('<config></config>', $config->data);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithXmlFromFile()
    {
        $file = 'Config/config0.xml';
        $config = Config::factory('xml', '_fixtures/' . $file, true);
        $this->assertType('\PHP\Manipulator\Config\Xml', $config);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithConfigMockFromFile()
    {
        $file = 'Config/config0.xml';
        $config = Config::factory('\Tests\Mock\ConfigMock', '_fixtures/' . $file, true);
        $this->assertType('\Tests\Mock\ConfigMock', $config);
        $this->assertEquals($this->getFixtureFileContent($file), $config->data);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testAddClassLoadersGetClassLoaders()
    {
        $config = $this->getConfig();
        $this->assertCount(0, $config->getClassLoaders());
        $config->addClassLoader('baa', 'foo');
        $config->addClassLoader('foo', 'baa');

        $classLoaders = $config->getClassLoaders();

        $this->assertArrayHasKey('baa', $classLoaders);
        $this->assertEquals('foo', $classLoaders['baa']);
        $this->assertArrayHasKey('foo', $classLoaders);
        $this->assertEquals('baa', $classLoaders['foo']);

        $this->assertCount(2, $classLoaders);
    }
}