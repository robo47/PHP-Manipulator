<?php

namespace PHP\Manipulator\Cli\Command;

use PHP\Manipulator\FileContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\ValueObject\ReadableFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowTokens extends Command
{
    protected function configure()
    {
        $this->setName('showTokens');
        $this->setDescription('Shows the tokens of a file');
        $this->setHelp('Shows you the tokens of a file');
        $def = [
            new InputArgument('file', InputArgument::REQUIRED, 'The file to show tokens for'),
        ];
        $this->setDefinition($def);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $fileArg = $input->getArgument('file');

        $file      = ReadableFile::createFromPath($fileArg);
        $container = FileContainer::createFromFile($file);

        $size = filesize($file->asString());

        $output->writeln(sprintf('Filesize: %u bytes', $size));
        $output->writeln(sprintf('Tokens: %u', count($container)));
        $output->writeln('');

        foreach ($container as $number => $token) {
            $output->writeln(str_pad($number.') ', 4, ' ').$this->formatTokenForOutput($token));
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function transformTokenValue($value)
    {
        $search  = [' ', "\t", "\r", "\n"];
        $replace = ['.', '\t', '\r', '\n'];

        return str_replace($search, $replace, $value);
    }

    /**
     * @param Token $token
     *
     * @return string
     */
    private function formatTokenForOutput(Token $token)
    {
        $name  = str_pad($token->getTokenName(), 28, ' ');
        $value = $this->transformTokenValue($token->getValue());

        return sprintf('%s | %s', $name, $value);
    }
}
