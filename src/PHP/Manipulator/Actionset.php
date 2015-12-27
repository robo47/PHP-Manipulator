<?php

namespace PHP\Manipulator;

/**
 * @todo Create IActionset when api is stable
 */
abstract class Actionset
{
    /**
     * Array with Options
     *
     * @var mixed[]
     */
    private $options = [];

    /**
     * @param mixed[] $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get Actions
     *
     * Returns array with all actions used by this actionset
     *
     * @return Action[]
     */
    abstract public function getActions();
}
