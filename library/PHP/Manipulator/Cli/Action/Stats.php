<?php

namespace PHP\Manipulator\Cli\Action;

use PHP\Manipulator\Cli\Action;
use PHP\Manipulator\Config;
use PHP\Manipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class Stats extends Action
{
    
    public function run()
    {
        $time = round(microtime(true) - $this->getCli()->getStartTime(), 2);
        echo 'Time: ' . $time . 's' . PHP_EOL;
        echo 'Memory: ' . round((memory_get_peak_usage() / (1024 * 1024)), 2) . 'mb' . PHP_EOL;
    }

    /**
     *
     * @return array
     */
    public function getConsoleOption()
    {
        $stats = new \ezcConsoleOption(
            's',
            'stats',
            \ezcConsoleInput::TYPE_NONE,
            null,
            false,
            'Show stats like used memory and time',
            '__LONG__',
            array(),
            array(),
            true,
            false,
            true
        );
        return array(
            $stats
        );
    }
}