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

        echo $input->getSynopsis() . PHP_EOL . PHP_EOL;

        foreach ($input->getOptions() as $option) {
            $curentOption = '  ' . str_pad("-{$option->short},", 6) . "--{$option->long}";
            echo \str_pad($curentOption, 30) . "  {$option->shorthelp}" . PHP_EOL;
        }
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
            '',
            array(),
            array(),
            true,
            false,
            true
            )
        );
    }
}