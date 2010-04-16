<?php

namespace PHP\Manipulator;

use PHP\Manipulator\AHelper;

abstract class Rule
extends AHelper
{

    /**
     * Performs the rule on the container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    abstract public function apply(TokenContainer $container);

    /**
     * Array with options
     *
     * @var array
     */
    protected $_options = array();

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->addOptions($options);
        $this->init();
    }

    /**
     * @param array $options
     * @return \PHP\Manipulator\AHelper *Provides Fluent Interface*
     */
    public function addOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }
        return $this;
    }

    /**
     * @param string $option
     * @return boolean
     */
    public function hasOption($option)
    {
        if (isset($this->_options[$option])) {
            return true;
        }
        return false;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return \PHP\Manipulator\AHelper *Provides Fluent Interface*
     */
    public function setOption($option, $value)
    {
        $this->_options[$option] = $value;
        return $this;
    }

    /**
     * Returns options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Returns an options value
     *
     * @param string $option
     * @return mixed
     */
    public function getOption($option)
    {
        if (!$this->hasOption($option)) {
            $message = "Option '$option' not found";
            throw new \Exception($message);
        }
        return $this->_options[$option];
    }



    /**
     * Called from constructor for checking options, adding default options
     * whatever you want to do.
     */
    public function init()
    {

    }
}