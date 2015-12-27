<?php

namespace Tests\PHP\Manipulator;

use Baa\Foo\Action\FirstAction;
use Baa\Foo\Action\FourthAction;
use Baa\Foo\Action\ThirdAction;
use PHP\Manipulator\Config;
use PHP\Manipulator\Config\XmlConfig;
use PHP\Manipulator\Exception\ConfigException;
use Symfony\Component\Finder\Finder;
use Tests\Stub\ConfigStub;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Config
 */
class ConfigTest extends TestCase
{
    public function testConstructorSetsDefaultOptions()
    {
        $config = $this->getConfig();

        $options = $config->getOptions();

        $this->assertArrayHasKey('actionPrefix', $options);
        $this->assertSame('PHP\\Manipulator\\Action\\', $options['actionPrefix']);

        $this->assertArrayHasKey('actionsetPrefix', $options);
        $this->assertSame('PHP\\Manipulator\\Actionset\\', $options['actionsetPrefix']);

        $this->assertArrayHasKey('fileSuffix', $options);
        $this->assertSame('.php', $options['fileSuffix']);

        $this->assertArrayHasKey('defaultNewline', $options);
        $this->assertSame("\n", $options['defaultNewline']);

        $this->assertCount(4, $options);
    }

    public function testGetOption()
    {
        $config = $this->getConfig();

        $value = $config->getOption('actionPrefix');
        $this->assertSame('PHP\\Manipulator\\Action\\', $value);
    }

    public function testGetOptionThrowsExceptionIfOptionDoesNotExist()
    {
        $config = $this->getConfig();

        $optionName = 'nonExistingOption';
        $this->setExpectedException(ConfigException::class, $optionName, ConfigException::OPTION_NOT_FOUND);
        $config->getOption($optionName);
    }

    public function testAddOption()
    {
        $config = $this->getConfig();

        $config->addOption('foo', 'baa');
        $this->assertSame('baa', $config->getOption('foo'));
    }

    public function testAddClassloader()
    {
        $config = $this->getConfig();

        $config->addOption('foo', 'baa');
        $this->assertSame('baa', $config->getOption('foo'));
    }

    public function testAddClassloaderGetClassloaders()
    {
        $config = $this->getConfig();

        $config->addClassLoader('Foo', 'baa');
        $classloaders = $config->getClassLoaders();

        $this->assertCount(1, $classloaders);
        $this->assertArrayHasKey('Foo', $classloaders);
        $this->assertContains('baa', $classloaders);
    }

    public function testAddFile()
    {
        $config = $this->getConfig();

        $this->assertCount(0, $config->getFiles());

        $fluent = $config->addFile(TESTS_PATH.'/bootstrap.php');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $config->getFiles());
        $this->assertContains(getcwd().'/bootstrap.php', $config->getFiles());
    }

    public function testAddFileThrowsExceptionIfFileNotExists()
    {
        $config = $this->getConfig();

        $name = 'TestHelper.phpx';
        $this->setExpectedException(ConfigException::class, $name, ConfigException::FILE_NOT_FOUND);

        $config->addFile($name);
    }

    public function testAddFileThrowsExceptionIfFileNotExistsWithAbsolutePath()
    {
        $config = $this->getConfig();

        $file = getcwd().'/TestHelper.phpx';
        $this->setExpectedException(ConfigException::class, $file, ConfigException::FILE_NOT_FOUND);
        $config->addFile($file);
    }

    public function testAddDirectory()
    {
        $config = $this->getConfig();

        $this->assertCount(0, $config->getFiles());

        $fluent = $config->addDirectory(TESTS_PATH.'_fixtures/Config/testDir0');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getFiles());
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir0/Baa.php', $config->getFiles());
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir0/Foo.php', $config->getFiles());
    }

    public function testAddIterator()
    {
        $config = $this->getConfig();

        $this->assertCount(0, $config->getFiles());

        $finder   = new Finder();
        $iterator = $finder->files()->name('*.php')->in(TESTS_PATH.'_fixtures/Config/testDir0');

        $fluent = $config->addIterator($iterator->getIterator());

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');

        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir0/Baa.php', $config->getFiles());
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir0/Foo.php', $config->getFiles());

        $this->assertCount(2, $config->getFiles());
    }

    public function testAddDirectoryThrowsExceptionIfDirectoryWasNotFound()
    {
        $config = $this->getConfig();

        $this->setExpectedException(ConfigException::class, '/non/existing/path', ConfigException::UNABLE_TO_OPEN_PATH);
        $config->addDirectory('/non/existing/path');
    }

    public function testAddActionset()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');
        $config->setOption('actionsetPrefix', 'Baa\\Foo\\Actionset\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addActionset('FirstActionset');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getActions());

        $actions = $config->getActions();

        $this->assertInstanceOf(ThirdAction::class, $actions[0]);
        $this->assertInstanceOf(FourthAction::class, $actions[1]);

        $this->assertSame('bla', $actions[0]->getOption('blub'));
        $this->assertSame('blub', $actions[1]->getOption('bla'));
    }

    public function testAddActionsetWithPrefix()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');
        $config->setOption('actionsetPrefix', 'Baa\\Foo\\Actionset\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addActionset('FirstActionset', 'Foo\\Baa\\Actionset\\');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getActions());

        $actions = $config->getActions();

        $this->assertInstanceOf('Foo\\Baa\\Action\\ThirdAction', $actions[0]);
        $this->assertInstanceOf('Foo\\Baa\\Action\\FourthAction', $actions[1]);

        $this->assertSame('bla', $actions[0]->getOption('blub'));
        $this->assertSame('blub', $actions[1]->getOption('bla'));
    }

    public function testAddAction()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction');

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertInstanceOf(FirstAction::class, $actions[0]);
    }

    public function testAddActionWithOptions()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', null, ['baa' => 'foo', 'blub' => 'bla']);

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertInstanceOf(FirstAction::class, $actions[0]);

        $action = $actions[0];
        $this->assertSame('foo', $action->getOption('baa'));
        $this->assertSame('bla', $action->getOption('blub'));
    }

    public function testAddActionWithPrefix()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', 'Foo\\Baa\\Action\\');

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertInstanceOf('Foo\\Baa\\Action\\FirstAction', $actions[0]);
    }

    public function testAddActionWithPrefixAndOptions()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', 'Foo\\Baa\\Action\\', ['baa' => 'foo', 'blub' => 'bla']);

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertInstanceOf('Foo\\Baa\\Action\\FirstAction', $actions[0]);

        $action = $actions[0];
        $this->assertSame('foo', $action->getOption('baa'));
        $this->assertSame('bla', $action->getOption('blub'));
    }

    public function testGetFileContent()
    {
        $file    = 'Config/config0.xml';
        $content = Config::getFileContent(TESTS_PATH.'_fixtures/'.$file);
        $this->assertSame($this->getFixtureFileContent($file), $content);
    }

    public function testGetFileContentThrowExceptionOnNonExistingFile()
    {
        $file = '/non/existing/file.php';
        $this->setExpectedException(ConfigException::class, $file, ConfigException::UNABLE_TO_READ_FILE);
        Config::getFileContent($file);
    }

    public function testGetFileContentThrowExceptionIfPathReferencesADirectory()
    {
        $file = 'Config/';
        $path = TESTS_PATH.'_fixtures/'.$file;
        $this->setExpectedException(ConfigException::class, $path, ConfigException::UNABLE_TO_READ_FILE);
        Config::getFileContent($path);
    }

    public function testFactoryWithXmlFromCode()
    {
        $config = Config::factory('xml', '<config></config>', false);
        $this->assertInstanceOf(XmlConfig::class, $config);
    }

    public function testFactoryWithConfigStubFromCode()
    {
        $config = Config::factory(ConfigStub::class, '<config></config>', false);
        $this->assertInstanceOf(ConfigStub::class, $config);
        $this->assertSame('<config></config>', $config->data);
    }

    public function testFactoryWithXmlFromFile()
    {
        $file   = 'Config/config0.xml';
        $config = Config::factory('xml', TESTS_PATH.'_fixtures/'.$file, true);
        $this->assertInstanceOf(XmlConfig::class, $config);
    }

    public function testFactoryWithConfigStubFromFile()
    {
        $file   = 'Config/config0.xml';
        $config = Config::factory(ConfigStub::class, TESTS_PATH.'_fixtures/'.$file, true);
        $this->assertInstanceOf(ConfigStub::class, $config);
        $this->assertSame($this->getFixtureFileContent($file), $config->data);
    }

    public function testAddClassLoadersGetClassLoaders()
    {
        $config = $this->getConfig();
        $this->assertCount(0, $config->getClassLoaders());
        $config->addClassLoader('baa', 'foo');
        $config->addClassLoader('foo', 'baa');

        $classLoaders = $config->getClassLoaders();

        $this->assertArrayHasKey('baa', $classLoaders);
        $this->assertSame('foo', $classLoaders['baa']);
        $this->assertArrayHasKey('foo', $classLoaders);
        $this->assertSame('baa', $classLoaders['foo']);

        $this->assertCount(2, $classLoaders);
    }
}
