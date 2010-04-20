<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;

abstract class Ruleset
{

    /**
     * Array with Options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->_options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Get Rules
     *
     * Returns array with all Rules used by this ruleset
     *
     * @return array
     */
    abstract public function getRules();

}