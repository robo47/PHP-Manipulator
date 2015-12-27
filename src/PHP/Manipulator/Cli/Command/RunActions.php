<?php

namespace PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Action;
use PHP\Manipulator\Config;
use PHP\Manipulator\Config\XmlConfig;
use PHP\Manipulator\Exception\CliException;
use PHP\Manipulator\FileContainer;
use PHP\Manipulator\ValueObject\ReadableFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PHP\Manipulator\Exception\PHPManipulatorException;

class RunActions extends Command
{
    protected function configure()
    {
        $this->setName('runActions');
        $this->setDescription('Runs actions from config an runs them');
        $def = [
            new InputOption('--config', null, InputOption::VALUE_OPTIONAL, 'the config used', 'phpmanipulator.xml'),
        ];
        $this->setDefinition($def);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getOption('config');

        $config = $this->getConfig($configFile);

        $files   = $config->getFiles();
        $actions = $config->getActions();

        $filesCount   = count($files);
        $actionsCount = count($actions);

        if ($filesCount === 0) {
            $output->writeln('No files found');

            return;
        }
        if ($actionsCount === 0) {
            $output->writeln('No actions found');

            return;
        }

        $filesDone = 1;
        $message   = sprintf('Processing %u files and %u actions', $filesCount, $actionsCount);
        $output->writeln($message);
        foreach ($files as $file) {
            $message = sprintf('File: %s (%u/%u)', $file, $filesDone, $filesCount);
            $output->writeln($message);
            $container = FileContainer::createFromFile(ReadableFile::createFromPath($file));
            foreach ($actions as $action) {
                $action->run($container);
                $output->writeln(sprintf('    Action: %s', get_class($action)));
            }
            $container->save();
            $filesDone++;
        }

        $output->writeln('');
        $output->writeln('Applied all actions ');
    }

    /**
     * @param string $configFile
     *
     * @return XmlConfig
     */
    private function getConfig($configFile)
    {
        try {
            return Config::factory('xml', $configFile, true);
        } catch (PHPManipulatorException $exception) {
            $message = sprintf('Unable to load config "%s": %s', $configFile, $exception->getMessage());
            throw new CliException($message, CliException::UNABLE_TO_LOAD_CONFIG, $exception);
        }
    }
}
