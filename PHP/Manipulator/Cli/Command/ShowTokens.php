<?php

namespace PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Token;
use PHP\Manipulator\FileContainer;
use Symfony\Components\Console\Input\InputInterface;
use Symfony\Components\Console\Output\OutputInterface;
use Symfony\Components\Console\Command\Command;
use Symfony\Components\Console\Input\InputArgument;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class ShowTokens extends Command
{
    protected function configure()
    {
        $this->setName('showTokens');
        $this->setDescription('Shows the tokens of a file');
        $this->setHelp('Shows you the tokens of a file');
        $def = array(
            new InputArgument('file', InputArgument::REQUIRED, 'The file to show tokens for')
        );
        $this->setDefinition($def);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $fileArg = $input->getArgument('file');
        $file = realpath($fileArg);
        if (!\file_exists($file) || !\is_readable($file) || !\is_file($file)) {
            throw new \Exception('Unable to open file: ' . $fileArg);
        }

        $container = new FileContainer($file);

        $size = filesize($file);

        $output->write('Filesize: ' . $size . ' bytes' . PHP_EOL);
        $output->write('Tokens: ' . count($container) . PHP_EOL . PHP_EOL);

        foreach ($container as $number => $token) {
            $output->write(str_pad($number . ') ', 4, ' ') . $this->printToken($token) . PHP_EOL);
        }
    }

    /**
     * @param string $value
     * @return string
     */
    public function transformTokenValue($value)
    {
        $value = str_replace(' ', '.', $value);
        $value = str_replace("\t", '\t', $value);
        $value = str_replace("\r", '\r', $value);
        $value = str_replace("\n", '\n', $value);

        return str_replace('', '', $value);
    }

    /**
     * @param \PHP\Manipulator\Token $token
     */
    public function printToken(Token $token)
    {
        $name = str_pad($token->getTokenName(), 28, ' ');
        $value = $this->transformTokenValue($token->getValue());
        return $name . ' | ' . $value;
    }
}