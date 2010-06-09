<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;

// @todo Create IActionset when api is stable
abstract class Actionset
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
     * Get Actions
     *
     * Returns array with all actions used by this actionset
     *
     * @return array
     */
    abstract public function getActions();

}