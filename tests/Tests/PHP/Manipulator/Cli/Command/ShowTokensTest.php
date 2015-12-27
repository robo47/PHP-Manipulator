<?php

namespace Tests\PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Cli\Command\ShowTokens;
use PHP\Manipulator\Exception\FileException;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

/**
 * @covers PHP\Manipulator\Cli\Command\ShowTokens
 */
class ShowTokensTest extends TestCase
{
    public function testExecute()
    {
        $tester = new CommandTester(new ShowTokens());

        $tester->execute(['file' => TESTS_PATH.'/_fixtures/Cli/Command/ShowTokens/helloWorld.php']);
        $this->assertSame('Filesize: 26 bytes
Tokens: 6

0)  T_OPEN_TAG                   | <?php\n
1)  T_WHITESPACE                 | \n
2)  T_ECHO                       | echo
3)  T_WHITESPACE                 | .
4)  T_CONSTANT_ENCAPSED_STRING   | \'hello.world\'
5)  UNKNOWN                      | ;
', $tester->getDisplay());
    }

    public function testExecuteThrowsExceptionIfFileIsNotOpenable()
    {
        $tester = new CommandTester(new ShowTokens());

        $this->setExpectedException(
            FileException::class,
            'nonExistingFile.php',
            FileException::EXPECTED_FILE_TO_EXIST
        );
        $tester->execute([
            'file' => TESTS_PATH.'/_fixtures/nonExistingFile.php',
        ]);
    }
}
