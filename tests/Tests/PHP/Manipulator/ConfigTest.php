<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\Config;
use Symfony\Component\Finder\Finder;
use Tests\Stub\ConfigStub;

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
        $this->assertEquals('PHP\\Manipulator\\Action\\', $options['actionPrefix']);

        $this->assertArrayHasKey('actionsetPrefix', $options);
        $this->assertEquals('PHP\\Manipulator\\Actionset\\', $options['actionsetPrefix']);

        $this->assertArrayHasKey('fileSuffix', $options);
        $this->assertEquals('.php', $options['fileSuffix']);

        $this->assertArrayHasKey('defaultNewline', $options);
        $this->assertEquals("\n", $options['defaultNewline']);

        $this->assertCount(4, $options);
    }

    /**
     * @covers \PHP\Manipulator\Config::getOption
     */
    public function testGetOption()
    {
        $config = $this->getConfig();

        $value = $config->getOption('actionPrefix');
        $this->assertEquals('PHP\\Manipulator\\Action\\', $value);
    }

    /**
     * @covers \PHP\Manipulator\Config::getOption
     */
    public function testGetOptionThrowsExceptionIfOptionDoesNotExist()
    {
        $config = $this->getConfig();

        try {
            $config->getOption('nonExistingOption');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('Option "nonExistingOption" does not exist', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Config::addOption
     */
    public function testAddOption()
    {
        $config = $this->getConfig();

        $config->addOption('foo', 'baa');
        $this->assertEquals('baa', $config->getOption('foo'));
    }

    /**
     * @covers \PHP\Manipulator\Config::addOption
     */
    public function testAddClassloader()
    {
        $config = $this->getConfig();

        $config->addOption('foo', 'baa');
        $this->assertEquals('baa', $config->getOption('foo'));
    }

    /**
     * @covers \PHP\Manipulator\Config::addClassLoader
     * @covers \PHP\Manipulator\Config::getClassLoaders
     */
    public function testAddClassloaderGetClassloaders()
    {
        $config = $this->getConfig();

        $config->addClassLoader('Foo', 'baa');
        $classloaders = $config->getClassLoaders();

        $this->assertCount(1, $classloaders);
        $this->assertArrayHasKey('Foo', $classloaders);
        $this->assertContains('baa', $classloaders);
    }

    /**
     * @covers \PHP\Manipulator\Config::addFile
     * @covers \PHP\Manipulator\Config::getFiles
     */
    public function testAddFile()
    {
        $config = $this->getConfig();

        $this->assertCount(0, $config->getFiles());

        $fluent = $config->addFile(TESTS_PATH . '/bootstrap.php');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $config->getFiles());
        $this->assertContains(getcwd() . '/bootstrap.php', $config->getFiles());
    }

    /**
     * @covers \PHP\Manipulator\Config::addFile
     */
    public function testAddFileThrowsExceptionIfFileNotExists()
    {
        $config = $this->getConfig();

        try {
            $config->addFile('TestHelper.phpx');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('File TestHelper.phpx not found', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Config::addFile
     */
    public function testAddFileThrowsExceptionIfFileNotExistsWithAbsolutePath()
    {
        $config = $this->getConfig();

        try {
            $config->addFile(getcwd() . '/TestHelper.phpx');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('File ' . getcwd() . '/TestHelper.phpx not found', $e->getMessage(), 'Wrong exception message');
        }
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

        $fluent = $config->addDirectory(TESTS_PATH . '_fixtures/Config/testDir0');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getFiles());
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir0/Baa.php', $config->getFiles());
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir0/Foo.php', $config->getFiles());
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
        $iterator = $finder->files()->name('*.php')->in(TESTS_PATH . '_fixtures/Config/testDir0');

        $fluent = $config->addIterator($iterator->getIterator());


        $this->assertSame($config, $fluent, 'Does not provide fluent interface');

        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir0/Baa.php', $config->getFiles());
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir0/Foo.php', $config->getFiles());

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
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');
        $config->setOption('actionsetPrefix', 'Baa\\Foo\\Actionset\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addActionset('FirstActionset');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getActions());

        $actions = $config->getActions();

        $this->assertInstanceOf('Baa\\Foo\\Action\\ThirdAction', $actions[0]);
        $this->assertInstanceOf('Baa\\Foo\\Action\\FourthAction', $actions[1]);

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
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');
        $config->setOption('actionsetPrefix', 'Baa\\Foo\\Actionset\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addActionset('FirstActionset', 'Foo\\Baa\\Actionset\\');

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(2, $config->getActions());

        $actions = $config->getActions();

        $this->assertInstanceOf('Foo\\Baa\\Action\\ThirdAction', $actions[0]);
        $this->assertInstanceOf('Foo\\Baa\\Action\\FourthAction', $actions[1]);

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
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction');

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertInstanceOf('Baa\\Foo\\Action\\FirstAction', $actions[0]);
    }

    /**
     * @covers \PHP\Manipulator\Config::addAction
     * @covers \PHP\Manipulator\Config::getActions
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddActionWithOptions()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', null, array('baa' => 'foo', 'blub' => 'bla'));

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertInstanceOf('Baa\\Foo\\Action\\FirstAction', $actions[0]);

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
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', 'Foo\\Baa\\Action\\');

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertInstanceOf('Foo\\Baa\\Action\\FirstAction', $actions[0]);
    }

    /**
     * @covers \PHP\Manipulator\Config::addAction
     * @covers \PHP\Manipulator\Config::getActions
     * @covers \PHP\Manipulator\Config::<protected>
     */
    public function testAddActionWithPrefixAndOptions()
    {
        $config = $this->getConfig();
        $config->setOption('actionPrefix', 'Baa\\Foo\\Action\\');

        $this->assertCount(0, $config->getActions());

        $fluent = $config->addAction('FirstAction', 'Foo\\Baa\\Action\\', array('baa' => 'foo', 'blub' => 'bla'));

        $actions = $config->getActions();

        $this->assertSame($config, $fluent, 'Does not provide fluent interface');
        $this->assertCount(1, $actions);
        $this->assertInstanceOf('Foo\\Baa\\Action\\FirstAction', $actions[0]);

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
        $content = Config::getFileContent(TESTS_PATH . '_fixtures/' . $file);
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
        $path = TESTS_PATH . '_fixtures/' . $file;
        try {
            Config::getFileContent($path);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals("Unable to read file: ".$path, $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithXmlFromCode()
    {
        $config = Config::factory('xml', '<config></config>', false);
        $this->assertInstanceOf('PHP\\Manipulator\\Config\\Xml', $config);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithConfigStubFromCode()
    {
        $config = Config::factory('Tests\\Stub\\ConfigStub', '<config></config>', false);
        $this->assertInstanceOf('Tests\\Stub\\ConfigStub', $config);
        $this->assertEquals('<config></config>', $config->data);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithXmlFromFile()
    {
        $file = 'Config/config0.xml';
        $config = Config::factory('xml', TESTS_PATH . '_fixtures/' . $file, true);
        $this->assertInstanceOf('PHP\Manipulator\Config\Xml', $config);
    }

    /**
     * @covers \PHP\Manipulator\Config::factory
     */
    public function testFactoryWithConfigStubFromFile()
    {
        $file = 'Config/config0.xml';
        $config = Config::factory('\Tests\Stub\ConfigStub', TESTS_PATH . '_fixtures/' . $file, true);
        $this->assertInstanceOf('Tests\Stub\ConfigStub', $config);
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