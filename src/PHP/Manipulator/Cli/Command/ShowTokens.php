<?php

namespace PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Token;
use PHP\Manipulator\FileContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
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
