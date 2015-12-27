<?php

namespace PHP\Manipulator;

use PHP\Manipulator\Exception\ActionException;

abstract class Action extends AHelper
{
    /**
     * @var mixed[]
     */
    private $options = [];

    /**
     * @param TokenContainer $container
     */
    abstract public function run(TokenContainer $container);

    /**
     * @param mixed[] $options
     */
    public function __construct(array $options = [])
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }
        $this->init();
    }

    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption($option)
    {
        if (isset($this->options[$option])) {
            return true;
        }

        return false;
    }

    /**
     * @param string $option
     * @param mixed  $value
     *
     * @return AHelper
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $option
     *
     * @return mixed
     */
    public function getOption($option)
    {
        if (!$this->hasOption($option)) {
            $message = sprintf('Option "%s" not found', $option);
            throw new ActionException($message, ActionException::NO_OPTION_BY_NAME);
        }

        return $this->options[$option];
    }

    public function init()
    {
    }
}
