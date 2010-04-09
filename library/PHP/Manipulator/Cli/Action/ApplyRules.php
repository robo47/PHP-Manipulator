<?php

namespace PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli\Action;
use PHP\Manipulator\Cli\Config;
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
            $config = Config::factory('xml', $configFile, true);
        } catch(\Exception $e) {
            $output->outputLine('Unable to load config: ' . $configFile);
            return;
        }
        /* @var $config PHP\Manipulator\Cli\Config\Xml */

        $files = $config->getFiles();
        $rules = $config->getRules();

        $filesCount = count($files);
        $rulesCount = count($rules);

        $steps = $filesCount * $rulesCount;

        // Create progress bar itself
        $progress = new \ezcConsoleProgressbar($output, $steps, array('step' => 1));

        $progress->options->emptyChar = '-';
        $progress->options->progressChar = '#';
        $progress->options->formatString = "Processing files %act%/%max% kb [%bar%]";

        // Perform actions
        $i = 0;
        foreach ($files as $file) {
            $container = TokenContainer::createFromFile($file);
            foreach ($rules as $rule) {
                /* @var $rule \PHP\Manipulator\Rule */
                $rule->applyRuleToTokens($container);
                $progress->advance();
            }
            $container->saveToFile($file);
        }

        // Finish progress bar and jump to next line.
        $progress->finish();

        $output->outputText("Applied all rules \n", 'success');
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