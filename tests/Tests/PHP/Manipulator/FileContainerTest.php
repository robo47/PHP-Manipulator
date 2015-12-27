<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\FileContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\ValueObject\ReadableFile;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\FileContainer
 */
class FileContainerTest extends TestCase
{
    public function setUp()
    {
        file_put_contents(TESTS_PATH.'tmp/test.php', '<?php echo $foo; ?>');
    }

    public function tearDown()
    {
        @unlink(TESTS_PATH.'tmp/test.php');
        @unlink(TESTS_PATH.'tmp/test2.php');
    }

    public function testContainer()
    {
        $fileContainer = FileContainer::createFromFile(ReadableFile::createFromPath(TESTS_PATH.'tmp/test.php'));
        $this->assertInstanceOf(FileContainer::class, $fileContainer);
    }

    public function testConstruct()
    {
        $container = FileContainer::createFromFile(ReadableFile::createFromPath(TESTS_PATH.'tmp/test.php'));
        $this->assertCount(7, $container->getIterator());
    }

    public function testGetFile()
    {
        $container = FileContainer::createFromFile(ReadableFile::createFromPath(TESTS_PATH.'tmp/test.php'));
        $this->assertSame(TESTS_PATH.'tmp/test.php', $container->getFile());
    }

    public function testSave()
    {
        $container = FileContainer::createFromFile(ReadableFile::createFromPath(TESTS_PATH.'tmp/test.php'));
        $container->removeTokens($container->toArray());
        $container[] = Token::createFromValueAndType('<foo>', T_INLINE_HTML);
        $container->save();
        $this->assertSame('<foo>', file_get_contents(TESTS_PATH.'tmp/test.php'));
    }

    public function testSaveTo()
    {
        $container = FileContainer::createFromFile(ReadableFile::createFromPath(TESTS_PATH.'tmp/test.php'));
        $container->removeTokens($container->toArray());
        $container[] = Token::createFromValueAndType('<foo>', T_INLINE_HTML);
        $container->saveTo(TESTS_PATH.'tmp/test2.php');

        $this->assertFileExists(TESTS_PATH.'tmp/test2.php');
        $this->assertSame('<foo>', file_get_contents(TESTS_PATH.'tmp/test2.php'));
    }

    public function testSaveThrowsExceptionIfFileIsNotWriteable()
    {
        $this->markTestIncomplete('not implemented yet');
    }
}
