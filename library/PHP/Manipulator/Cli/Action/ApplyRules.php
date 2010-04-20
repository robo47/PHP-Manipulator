<?php

namespace PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli\Action;
use PHP\Manipulator\Config;
use PHP\Manipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ApplyRules extends Action
{
    
    public function run()
    {
        $input = $this->getCli()->getConsoleInput();
        $output = $this->getCli()->getConsoleOutput();

        $configFile = $input->getOption('config')->value;

        try {
            // @todo make dynamic via cli/
            $config = Config::factory('xml', $configFile, true);
        } catch (\Exception $e) {
            $output->outputLine('Unable to load config: ' . $configFile . PHP_EOL . 'error: ' . $e->getMessage());
            return;
        }
        /* @var $config PHP\Manipulator\Config\Xml */

        $files = $config->getFiles();
        $rules = $config->getRules();

        $filesCount = count($files);
        $rulesCount = count($rules);

        $steps = $filesCount * $rulesCount;

        $options = array(
            'step' => 1,
        );

        if ($filesCount == 0) {
            $output->outputLine('No files found');
            return;
        }
        if ($rulesCount == 0) {
            $output->outputLine('No rules found');
            return;
        }

        // Create progress bar itself
        $progress = new \ezcConsoleProgressbar($output, $steps, $options);

        $progress->options->emptyChar = '-';
        $progress->options->progressChar = '#';
        $progress->options->formatString = "Processing files %act% / %max%  [%bar%]";

        // Perform actions
        $i = 0;
        foreach ($files as $file) {
            $container = new FileContainer($file);
            foreach ($rules as $rule) {
                /* @var $rule \PHP\Manipulator\Rule */
                $rule->apply($container);
                $progress->advance();
            }
            $container->save();
        }

        // Finish progress bar and jump to next line.
        $progress->finish();

        $output->outputText(PHP_EOL . "Applied all rules " . PHP_EOL, 'success');
    }

    /**
     *
     * @return array
     */
    public function getConsoleOption()
    {
        $ar = new \ezcConsoleOption(
            'ar',
            'applyrules',
            \ezcConsoleInput::TYPE_NONE,
            null,
            false,
            'Apply rules to Files',
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