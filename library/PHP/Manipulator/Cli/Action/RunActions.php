<?php

namespace PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli\Action;
use PHP\Manipulator\Config;
use PHP\Manipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\FileContainer;

class RunActions extends Action
{
    /**
     * @param string $configFile
     * @return \PHP\Manipulator\Config\Xml
     */
    protected function _getConfig($configFile)
    {
        try {
            // @todo make extension dynamic via cli ?
            return Config::factory('xml', $configFile, true);
        } catch (\Exception $e) {
            throw new \Exception('Unable to load config: ' . $configFile . PHP_EOL . 'error: ' . $e->getMessage());
        }
    }

    public function run()
    {
        $input = $this->getCli()->getConsoleInput();
        $output = $this->getCli()->getConsoleOutput();

        $configFile = $input->getOption('config')->value;

        $config = $this->_getConfig($configFile);

        $files = $config->getFiles();
        $actions = $config->getActions();

        $filesCount = count($files);
        $actionsCount = count($actions);

        $steps = $filesCount * $actionsCount;

        $options = array(
            'step' => 1,
        );

        if ($filesCount === 0) {
            $output->outputLine('No files found');
            return;
        }
        if ($actionsCount === 0) {
            $output->outputLine('No actions found');
            return;
        }

        // Create progress bar itself
        $progress = new \ezcConsoleProgressbar($output, $steps, $options);

        $progress->options->emptyChar = '-';
        $progress->options->progressChar = '#';
        $progress->options->formatString = '[%bar%]  %act% / %max%';

        // @todo timings in debug-mode ?
        // Perform actions
        echo 'Processing ' . $filesCount . ' files and ' . $actionsCount . ' actions' . PHP_EOL;
        foreach ($files as $file) {
            //echo 'File: ' . $file . PHP_EOL;
            $container = new FileContainer($file);
            foreach ($actions as $action) {
                /* @var $action \PHP\Manipulator\Action */
                $action->run($container);
                $progress->advance();
                //echo PHP_EOL . '    Action: ' . get_class($action) . PHP_EOL;
            }
            $container->save();
        }

        // Finish progress bar and jump to next line.
        $progress->finish();

        $output->outputText(PHP_EOL . 'Applied all actions ' . PHP_EOL, 'success');
    }

    /**
     *
     * @return array
     */
    public function getConsoleOption()
    {
        $ar = new \ezcConsoleOption(
            'ra',
            'RunActions',
            \ezcConsoleInput::TYPE_NONE,
            null,
            false,
            'Apply actions to Files',
            '__LONG__',
            array(),
            array(),
            true,
            false,
            true
        );

        $config = new \ezcConsoleOption(
            'c',
            'config',
            \ezcConsoleInput::TYPE_STRING,
            null,
            false,
            'Path to the config-file',
            '__LONG__',
            array(),
            array(),
            true,
            false,
            true
        );

        $ar->addDependency(new \ezcConsoleOptionRule($config));

        return array(
            $ar,
            $config
        );
    }
}