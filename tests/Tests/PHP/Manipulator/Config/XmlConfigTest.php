<?php

namespace Tests\PHP\Manipulator\Config;

use Baa\Foo\Action\FirstAction;
use PHP\Manipulator\Config;
use PHP\Manipulator\Exception\ConfigException;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Config\XmlConfig
 */
class XmlConfigTest extends TestCase
{
    public function testConfig()
    {
        $config = $this->getXmlConfig(0);

        $options = $config->getOptions();
        $this->assertInternalType('array', $options);

        $this->assertArrayHasKey('actionPrefix', $options);
        $this->assertSame('Baa\\Foo\\Action\\', $options['actionPrefix']);
        $this->assertArrayHasKey('actionsetPrefix', $options);
        $this->assertSame('Baa\Foo\Actionset\\', $options['actionsetPrefix']);
        $this->assertArrayHasKey('fileSuffix', $options);
        $this->assertSame('.phtml', $options['fileSuffix']);
        $this->assertArrayHasKey('defaultNewline', $options);

        $this->assertCount(4, $options);

        $this->assertSame("\n", $options['defaultNewline']);

        $actions = $config->getActions();
        $this->assertInternalType('array', $actions);

        $this->assertInstanceOf(FirstAction::class, $actions[0]);

        $this->assertSame('foo', $actions[0]->getOption('baa'));

        $this->assertInstanceOf('Foo\\Baa\\Action\\SecondAction', $actions[1]);
        $this->assertSame('baa', $actions[1]->getOption('foo'));
        $this->assertTrue($actions[1]->getOption('someTrueBoolean'));
        $this->assertFalse($actions[1]->getOption('someFalseBoolean'));

        $this->assertInstanceOf('Baa\\Foo\\Action\\ThirdAction', $actions[2]);
        $this->assertSame('bla', $actions[2]->getOption('blub'));

        $this->assertInstanceOf('Baa\\Foo\\Action\\FourthAction', $actions[3]);
        $this->assertSame('blub', $actions[3]->getOption('bla'));

        $this->assertInstanceOf('Foo\\Baa\\Action\\FifthAction', $actions[4]);
        $this->assertSame('foo', $actions[4]->getOption('baa'));

        $this->assertInstanceOf('Foo\\Baa\\Action\\SixthsAction', $actions[5]);
        $this->assertSame('baa', $actions[5]->getOption('foo'));

        $this->assertCount(6, $actions);

        $files = $config->getFiles();
        $this->assertInternalType('array', $files);

        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir0/Blub.phtml', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir1/Baafoo.php', $files);

        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Baafoo.php', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Baa.php', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Foo.php', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Blub.php', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Baa.phtml', $files);

        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir3/Baa.php', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir3/Foo.php', $files);

        $this->assertCount(9, $files);
    }

    public function testActionOptionsCastWorks()
    {
        $config = $this->getXmlConfig(2);

        $actions = $config->getActions();
        $this->assertInternalType('array', $actions);
        $this->assertCount(1, $actions);

        $action = $actions[0];

        $this->assertInstanceOf(FirstAction::class, $action);

        $this->assertInternalType('integer', $action->getOption('integerOne'));
        $this->assertSame(1, $action->getOption('integerOne'));

        $this->assertInternalType('integer', $action->getOption('integerTwenty'));
        $this->assertSame(20, $action->getOption('integerTwenty'));

        $this->assertInternalType('bool', $action->getOption('booleanTrue'));
        $this->assertSame(true, $action->getOption('booleanTrue'));

        $this->assertInternalType('bool', $action->getOption('booleanFalse'));
        $this->assertSame(false, $action->getOption('booleanFalse'));

        $this->assertInternalType('array', $action->getOption('array'));
        $this->assertSame(['foo'], $action->getOption('array'));

        $this->assertInternalType('object', $action->getOption('object'));
        $this->assertEquals((object) 'foo', $action->getOption('object'));

        $this->assertInternalType('float', $action->getOption('real'));
        $this->assertSame(1.23, $action->getOption('real'));

        $this->assertInternalType('float', $action->getOption('float'));
        $this->assertSame(1.23, $action->getOption('float'));

        $this->assertInternalType('float', $action->getOption('double'));
        $this->assertSame(1.23, $action->getOption('double'));

        $this->assertInternalType('string', $action->getOption('linebreaks'));
        $this->assertSame("\n\r\n\r", $action->getOption('linebreaks'));

        $this->assertCount(10, $action->getOptions());
    }

    public function testInvalidCastOptionThrowsException()
    {
        $this->setExpectedException(ConfigException::class, '', ConfigException::UNKNOWN_CAST_OPTION);
        $this->getXmlConfig(6);
    }

    public function testClassLoadersAreRead()
    {
        $config = $this->getXmlConfig(3);

        $classLoaders = $config->getClassLoaders();

        $this->assertArrayHasKey('Foo', $classLoaders);
        $this->assertSame('/tmp/', $classLoaders['Foo']);
        $this->assertArrayHasKey('Baa', $classLoaders);
        $this->assertSame('/src/', $classLoaders['Baa']);

        $this->assertCount(2, $config->getClassLoaders());
    }

    public function testIteratorWithNameAndNotName()
    {
        $config = $this->getXmlConfig(5);

        $files = $config->getFiles();

        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Foo.php', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Blub.php', $files);

        $this->assertCount(2, $files);
    }

    public function testIteratorWithSize()
    {
        $config = $this->getXmlConfig(7);

        $files = $config->getFiles();

        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Baa.php', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Blub.phtml', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir2/Foo.php', $files);

        $this->assertCount(3, $files);
    }

    public function testIteratorWithExclude()
    {
        $config = $this->getXmlConfig(8);

        $files = $config->getFiles();

        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir3/Foo.php', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir3/Blub.phtml', $files);
        $this->assertContains(TESTS_PATH.'_fixtures/Config/testDir3/Baa.php', $files);

        $this->assertCount(3, $files);
    }

    public function testLoadingDefectXmlThrowsException()
    {
        $this->setExpectedException(
            ConfigException::class,
            '',
            ConfigException::XML_PARSE_ERROR
        );
        $this->getXmlConfig(4);
    }
}
