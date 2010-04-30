<?php

namespace PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli\Action;
use PHP\Manipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class Version extends Action
{
    public function run()
    {
        echo PHP_EOL .
        'Version: ' . Manipulator::VERSION . ' (' . Manipulator::GITHASH . ')' . PHP_EOL .
        'Author: Benjamin Steininger <robo47@robo47.net>' . PHP_EOL .
        'Homepage: TBD' . PHP_EOL .
        'License: New BSD License' . PHP_EOL . PHP_EOL;
    }

    /**
     *
     * @return array
     */
    public function getConsoleOption()
    {
        return array (
            new \ezcConsoleOption(
                'v',
                'version',
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