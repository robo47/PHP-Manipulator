<?php

namespace PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli\Action;
use PHP\Manipulator\Cli\Config\Xml as XmlConfig;
use PHP\Manipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class ApplyRules extends Action
{

    public function run()
    {
        $output = $this->getCli()->getConsoleOutput();;
        $options = $this->getCli()->getOptions();
        var_dump($options['ApplyRules']->value);
        if ($options['ApplyRules']->value === true) {
            if (file_exists('./manipulator.xml')) {
                echo 'manip';
                $configFile = 'manipulator.xml';
            }
        } else {
            $configFile = $options['ApplyRules']->value;
        }
        try {
            $config = new XmlConfig($configFile);
        } catch(\Exception $e) {
            $output->outputLine('Unable to load config');
            return;
        }
        /* @var $config PHP\Manipulator\Cli\Config\Xml */

        $files = $config->getFiles();

        $rules = array();
        foreach($config->getRules() as $rule) {
            $rules[] = new $rule;
        }

        $rulesets = array();
        foreach($config->getRulesets() as $ruleset) {
            /* @var $ruleset \PHP\Manipulator\IRuleset */
            $rulesetRules = $ruleset->getRules();
            foreach($rulesetRules as $rule) {
                $rules[] = $rule;
            }
        }

        $manipulator = new Manipulator($rules, $files);
        //$manipulator->addRuleset($config->getRulesets());

        $filesCount = count($files);
        $rulesCount = count($rules);



        $steps = $filesCount * $rulesCount;

        // Create progress bar itself
        $progress = new ezcConsoleProgressbar($out, $steps, array('step' => 1));

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

        $out->outputText("Applied all rules \n", 'success');
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
            'Apply Rule to Files',
            '__LONG__',
            array(),
            array(),
            true,
            false,
            true
        );

        return array(
            $ar,
         );
    }
}