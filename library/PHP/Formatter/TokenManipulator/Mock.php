<?php

require_once 'PHP/Formatter/TokenManipulator/Abstract.php';

class PHP_Formatter_TokenManipulator_Mock
extends PHP_Formatter_TokenManipulator_Abstract
{

    /**
     * @var boolean
     */
    public static $return = true;
    
    public function manipulate(PHP_Formatter_Token $token, $params = null)
    {
        if ($this->hasOption('return')) {
            return $this->getOption('return');
        } else {
            return self::$return;
        }
    }
}
