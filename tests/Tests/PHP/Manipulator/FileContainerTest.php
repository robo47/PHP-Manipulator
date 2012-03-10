<?php

namespace Tests\PHP\Manipulator;

use PHP\Manipulator\FileContainer;
use PHP\Manipulator\Token;

/**
 * @group TokenContainer
 * @group FileContainer
 */
class FileContainerTest extends \Tests\TestCase
{

    public function setUp()
    {
        file_put_contents(TESTS_PATH . 'tmp/test.php', '<?php echo $foo; ?>');
    }

    public function tearDown()
    {
        @unlink(TESTS_PATH . 'tmp/test.php');
        @unlink(TESTS_PATH . 'tmp/test2.php');
    }


    /**
     * @covers \PHP\Manipulator\FileContainer
     */
    public function testContainer()
    {
        $reflection = new \ReflectionClass('PHP\Manipulator\FileContainer');
        $this->assertEquals('PHP\Manipulator\TokenContainer', $reflection->getParentClass()->getName());
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::__construct
     */
    public function testConstruct()
    {
        $container = new FileContainer(TESTS_PATH . 'tmp/test.php');
        $this->assertCount(7, $container->getIterator());
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::getFile
     */
    public function testGetFile()
    {
        $container = new FileContainer(TESTS_PATH . 'tmp/test.php');
        $this->assertEquals(TESTS_PATH . 'tmp/test.php', $container->getFile());
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::__construct
     */
    public function testConstructThrowsExceptionIfFileNotExists()
    {
        try {
            $container = new FileContainer('/path/to/not/existingFile');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('Unable to open file for reading: /path/to/not/existingFile', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::__construct
     */
    public function testConstructThrowsExceptionIfFileIsNoFile()
    {
        try {
            $container = new FileContainer(__DIR__);
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('Unable to open file for reading: ' . __DIR__, $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::save
     */
    public function testSave()
    {
        $container = new FileContainer(TESTS_PATH . 'tmp/test.php');
        $container->removeTokens($container->toArray());
        $container[] = new Token('<foo>', T_INLINE_HTML);
        $container->save();
        $this->assertEquals('<foo>', file_get_contents(TESTS_PATH . 'tmp/test.php'));
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::saveTo
     */
    public function testSaveTo()
    {
        $container = new FileContainer(TESTS_PATH . 'tmp/test.php');
        $container->removeTokens($container->toArray());
        $container[] = new Token('<foo>', T_INLINE_HTML);
        $container->saveTo(TESTS_PATH . 'tmp/test2.php');

        $this->assertFileExists(TESTS_PATH . 'tmp/test2.php');
        $this->assertEquals('<foo>', file_get_contents(TESTS_PATH . 'tmp/test2.php'));
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::save
     */
    public function testSaveThrowsExceptionIfFileIsNotWriteable()
    {
        $this->markTestIncomplete('not implemented yet');
    }

    /**
     * @covers \PHP\Manipulator\FileContainer::saveTo
     */
    public function testSaveToThrowsExceptionIfFileIsNotWriteable()
    {
        $container = new FileContainer(TESTS_PATH . 'tmp/test.php');
        try {
            $container->saveTo(TESTS_PATH . 'tmp/foo/test.php');
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('Unable to open file for writing: ' . TESTS_PATH . 'tmp/foo/test.php', $e->getMessage(), 'Wrong exception message');
        }
    }
}
