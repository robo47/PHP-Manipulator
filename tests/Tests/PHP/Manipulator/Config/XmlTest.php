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
        $this->assertInternalType('array', $options);

        $this->assertArrayHasKey('actionPrefix', $options);
        $this->assertEquals('Baa\\Foo\\Action\\', $options['actionPrefix']);
        $this->assertArrayHasKey('actionsetPrefix', $options);
        $this->assertEquals('Baa\Foo\Actionset\\', $options['actionsetPrefix']);
        $this->assertArrayHasKey('fileSuffix', $options);
        $this->assertEquals('.phtml', $options['fileSuffix']);
        $this->assertArrayHasKey('defaultNewline', $options);

        $this->assertCount(4, $options);

        $this->assertEquals("\n", $options['defaultNewline']);

        $actions = $config->getActions();
        $this->assertInternalType('array', $actions);

        $this->assertInstanceOf('Baa\\Foo\\Action\\FirstAction', $actions[0]);

        $this->assertEquals('foo', $actions[0]->getOption('baa'));

        $this->assertInstanceOf('Foo\\Baa\\Action\\SecondAction', $actions[1]);
        $this->assertEquals('baa', $actions[1]->getOption('foo'));
        $this->assertTrue($actions[1]->getOption('someTrueBoolean'));
        $this->assertFalse($actions[1]->getOption('someFalseBoolean'));

        $this->assertInstanceOf('Baa\\Foo\\Action\\ThirdAction', $actions[2]);
        $this->assertEquals('bla', $actions[2]->getOption('blub'));

        $this->assertInstanceOf('Baa\\Foo\\Action\\FourthAction', $actions[3]);
        $this->assertEquals('blub', $actions[3]->getOption('bla'));

        $this->assertInstanceOf('Foo\\Baa\\Action\\FifthAction', $actions[4]);
        $this->assertEquals('foo', $actions[4]->getOption('baa'));

        $this->assertInstanceOf('Foo\\Baa\\Action\\SixthsAction', $actions[5]);
        $this->assertEquals('baa', $actions[5]->getOption('foo'));

        $this->assertCount(6, $actions);

        $files = $config->getFiles();
        $this->assertInternalType('array', $files);

        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir0/Blub.phtml', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir1/Baafoo.php', $files);

        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Baafoo.php', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Baa.php', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Foo.php', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Blub.php', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Baa.phtml', $files);

        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir3/Baa.php', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir3/Foo.php', $files);

        $this->assertCount(9, $files);
    }

    /**
     * @covers \PHP\Manipulator\Config\Xml::_castValue
     */
    public function testActionOptionsCastWorks()
    {
        $config = $this->getXmlConfig(2);

        $actions = $config->getActions();
        $this->assertInternalType('array', $actions);
        $this->assertCount(1, $actions);

        $action = $actions[0];

        $this->assertInstanceOf('Baa\\Foo\\Action\\FirstAction', $action);
        /* @var $action \PHP\Manipulator\Action */

        $this->assertInternalType('integer', $action->getOption('integerOne'));
        $this->assertSame(1, $action->getOption('integerOne'));

        $this->assertInternalType('integer', $action->getOption('integerTwenty'));
        $this->assertSame(20, $action->getOption('integerTwenty'));

        $this->assertInternalType('bool', $action->getOption('booleanTrue'));
        $this->assertSame(true, $action->getOption('booleanTrue'));

        $this->assertInternalType('bool', $action->getOption('booleanFalse'));
        $this->assertSame(false, $action->getOption('booleanFalse'));

        $this->assertInternalType('array', $action->getOption('array'));
        $this->assertEquals(array('foo'), $action->getOption('array'));

        $this->assertInternalType('object', $action->getOption('object'));
        $this->assertEquals((object) 'foo', $action->getOption('object'));

        $this->assertInternalType('float', $action->getOption('real'));
        $this->assertEquals(1.23, $action->getOption('real'));

        $this->assertInternalType('float', $action->getOption('float'));
        $this->assertEquals(1.23, $action->getOption('float'));

        $this->assertInternalType('float', $action->getOption('double'));
        $this->assertEquals(1.23, $action->getOption('double'));

        $this->assertInternalType('string', $action->getOption('linebreaks'));
        $this->assertEquals("\n\r\n\r", $action->getOption('linebreaks'));

        $this->assertCount(10, $action->getOptions());
    }

    /**
     * @covers \PHP\Manipulator\Config\Xml::_castValue
     */
    public function testInvalidCastOptionThrowsException()
    {
        try {
            $config = $this->getXmlConfig(6);
            $this->fail('No exception thrown');
        } catch(\Exception $e) {
            $this->assertContains('unknown cast-type: foo', $e->getMessage());
        }
    }

    /**
     * @covers \PHP\Manipulator\Config\Xml::_parseClassLoaders
     * @covers \PHP\Manipulator\Config\Xml::_getAttributesAsArray
     */
    public function testClassLoadersAreRead()
    {
        $config = $this->getXmlConfig(3);

        $classLoaders = $config->getClassLoaders();

        $this->assertArrayHasKey('Foo', $classLoaders);
        $this->assertEquals('/tmp/', $classLoaders['Foo']);
        $this->assertArrayHasKey('Baa', $classLoaders);
        $this->assertEquals('/src/', $classLoaders['Baa']);

        $this->assertCount(2, $config->getClassLoaders());
    }


    /**
     * @covers \PHP\Manipulator\Config\Xml::_parseIterator
     */
    public function testIteratorWithNameAndNotName()
    {
        $config = $this->getXmlConfig(5);

        $files = $config->getFiles();

        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Foo.php', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Blub.php', $files);

        $this->assertCount(2, $files);
    }

    /**
     * @covers \PHP\Manipulator\Config\Xml::_parseIterator
     */
    public function testIteratorWithSize()
    {
        $config = $this->getXmlConfig(7);

        $files = $config->getFiles();

        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Baa.php', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Blub.phtml', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir2/Foo.php', $files);

        $this->assertCount(3, $files);
    }

    /**
     * @group foo
     * @covers \PHP\Manipulator\Config\Xml::_parseIterator
     */
    public function testIteratorWithExclude()
    {
        $config = $this->getXmlConfig(8);

        $files = $config->getFiles();

        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir3/Foo.php', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir3/Blub.phtml', $files);
        $this->assertContains(TESTS_PATH . '_fixtures/Config/testDir3/Baa.php', $files);

        $this->assertCount(3, $files);
    }

    /**
     * @covers \PHP\Manipulator\Config\Xml::_errorMessage
     * @covers \PHP\Manipulator\Config\Xml::_initConfig
     */
    public function testLoadingDefectXmlThrowsException()
    {
        try {
            $this->getXmlConfig(4);
            $this->fail('No exception thrown');
        } catch(\Exception $e) {
            $this->assertContains('Unable to parse data: ', $e->getMessage());
        }
    }
}