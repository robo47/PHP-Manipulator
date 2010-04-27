<?php

namespace PHP\Manipulator\Cli;

use PHP\Manipulator\Cli;

abstract class Action
{

    /**
     *
     * @var PHP\Manipulator\Cli
     */
    protected $_cli;

    /**
     *
     * @param \PHP\Manipulator\Cli $cli
     */
    public function __construct(Cli $cli)
    {
        $this->_cli = $cli;
        // @todo init options from input
    }

    /**
     *
     * @return \PHP\Manipulator\Cli
     */
    public function getCli()
    {
        return $this->_cli;
    }

    /**
     * Run the Action
     */
    abstract public function run();

    /**
     *
     * @return array Array of ezcConsoleOption
     */
    abstract public function getConsoleOption();

}