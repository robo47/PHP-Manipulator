<?php

namespace Tests\Mock;

use PHP\Manipulator\Config;

class ConfigMock extends Config
{
    /**
     * @var mixed
     */
    public $data;

    /**
     * @param mixed $data
     */
    protected function _initConfig($data)
    {
        $this->data = $data;
    }

    /**
     *
     * @param mixed $option
     * @param mixed $value
     */
    public function setOption($option, $value)
    {
        $this->_options[$option] = $value;
    }
}
