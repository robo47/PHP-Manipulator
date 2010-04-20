<?php

namespace Tests\PHP\Manipulator\Config;

use PHP\Manipulator\Config;

class NonAbstract extends Config
{
    
    public $data;
    
    protected function _initConfig($data)
    {
        $this->data = $data;
    }
    
    public function setOption($option, $value)
    {
        $this->_options[$option] = $value;
    }
}