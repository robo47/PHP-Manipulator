<?php

namespace PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli\Action;
use PHP\Manipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class Help extends Action
{

    public function run()
    {
        $input = $this->getCli()->getConsoleInput();
        $output = $this->getCli()->getConsoleOutput();

        //$help = $input->getSynopsis() . PHP_EOL . PHP_EOL;
        $help = $input->getHelp();

        $output->outputText($input->getSynopsis() . PHP_EOL. PHP_EOL);

        foreach ( $input->getOptions() as $option )
        {
            $curentOption = "-{$option->short}, --{$option->long}";

            $output->outputText(\str_pad($curentOption, 30) . "  {$option->shorthelp}" . PHP_EOL);
        }
        $output->outputText(PHP_EOL);
    }

    /**
     *
     * @return array
     */
    public function getConsoleOption()
    {
        return array (
            new \ezcConsoleOption(
                'h',
                'help',
                \ezcConsoleInput::TYPE_NONE,
                null,
                false,
                'Shows you the help-function',
                'Shows you the parameters',
                array(),
                array(),
                true,
                false,
                true
            )
        );
    }
}