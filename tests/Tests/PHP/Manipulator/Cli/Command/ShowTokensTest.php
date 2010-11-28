<?php

namespace Tests\PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Cli;
use PHP\Manipulator\Cli\Command\ShowTokens;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * @group Cli
 * @group Cli\Command
 * @group Cli\Command\ShowTokens
 */
class ShowTokensTest extends \Tests\TestCase
{
    public function setUp()
    {
        ob_start();
    }

    public function tearDown()
    {
        ob_end_clean();
    }

    /**
     * @covers PHP\Manipulator\Cli\Command\ShowTokens::execute
     */
    public function testExecute()
    {
        $command = new ShowTokens();

        $command->execute(new ArgvInput(array('showTokens', TESTS_PATH . '/_fixtures/Cli/Command/ShowTokens/helloWorld.php'), $command->getDefinition()), new StreamOutput(fopen('php://output', 'w')));
        $output = ob_get_contents();
        $this->assertEquals('Filesize: 26 bytes
Tokens: 6

0)  T_OPEN_TAG                   | <?php\n
1)  T_WHITESPACE                 | \n
2)  T_ECHO                       | echo
3)  T_WHITESPACE                 | .
4)  T_CONSTANT_ENCAPSED_STRING   | \'hello.world\'
5)  UNKNOWN                      | ;
', $output);
    }

    /**
     * @covers PHP\Manipulator\Cli\Command\ShowTokens::execute
     */
    public function testExecuteThrowsExceptionIfFileIsNotOpenable()
    {
        $command = new ShowTokens();

        try {
            $command->execute(new ArgvInput(array('showTokens', TESTS_PATH . '/_fixtures/nonExistingFile.php'), $command->getDefinition()), new StreamOutput(fopen('php://output', 'w')));
            $this->fail('Expected exception not thrown');
        } catch (\Exception $e) {
            $this->assertEquals('Unable to open file: ' . TESTS_PATH . '/_fixtures/nonExistingFile.php', $e->getMessage(), 'Wrong exception message');
        }
    }
}