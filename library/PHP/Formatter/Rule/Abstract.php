<?php

require_once 'PHP/Formatter/Rule/Interface.php';

abstract class PHP_Formatter_Rule_Abstract implements PHP_Formatter_Rule_Interface
{
    /**
     * Array with options
     * 
     * @var array
     */
    protected $_options = array();

    /**
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
        $this->_options = $options;
        $this->init();        
    }

    /**
     *
     * @param array $options
     * @return <type>
     */
    public function setOptions(array $options)
    {
        $this->_options = array();
        $this->addOptions($options);
        return $this;
    }

    /**
     *
     * @param array $options
     * @return <type>
     */
    public function addOptions(array $options)
    {
        foreach($options as $option => $value) {
            $this->setOption($option, $value);
        }
        return $this;
    }

    /**
     *
     * @param string $option
     * @param mixed $value
     * @return <type> 
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
        if (isset($this->_options[$option])) {
            return $this->_options[$option];
        } else {
            require_once 'PHP/Formatter/Exception.php';
            $message = "option '$option' not found";
            throw new PHP_Formatter_Exception($message);
        }
    }

    /**
     * Check Token Constraint
     *
     * @todo support for Constraints with other prefix ?
     * @param PHP_Formatter_TokenConstraint_Interface|string $constraint
     * @param PHP_Formatter_Token $token
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function checkTokenConstraint($constraint, PHP_Formatter_Token $token, $autoPrefix = true)
    {
        if (is_string($constraint)) {
            $constraintClass = $constraint;
            if ($autoPrefix) {
                $constraintClass = 'PHP_Formatter_TokenConstraint_' . $constraint;
            }
            if (!class_exists($constraintClass, false)) {
                require_once str_replace('_', '/', $constraintClass) . '.php';
            }
            $constraint = new $constraintClass();
        }
        /* @var $constraint PHP_Formatter_TokenConstraint_Interface */
        return $constraint->evaluate($token);
    }

    /**
     * Check TokenArray Constraint
     *
     * @todo support for Constraints with other prefix ?
     * @param PHP_Formatter_TokenContainerConstraint_Interface|string $constraint
     * @param PHP_Formatter_TokenContainer $tokenArray
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function checkTokenArrayConstraint($constraint, PHP_Formatter_TokenContainer $tokenArray, $autoPrefix = true)
    {
        if (is_string($constraint)) {
            $constraintClass = $constraint;
            if ($autoPrefix) {
                $constraintClass = 'PHP_Formatter_TokenContainerConstraint_' . $constraint;
            }
            if (!class_exists($constraintClass, false)) {
                require_once str_replace('_', '/', $constraintClass) . '.php';
            }
            $constraint = new $constraintClass();
        }
        if (!$constraint instanceof PHP_Formatter_TokenContainerConstraint_Interface) {
            require_once 'PHP/Formatter/Exception.php';
            $message = 'constraint is not instance of PHP_Formatter_TokenContainerConstraint_Interface';
            throw new PHP_Formatter_Exception($message);
        }
        /* @var $constraint PHP_Formatter_TokenContainerConstraint_Interface */
        return $constraint->evaluate($tokenArray);
    }

    /**
     * Called from constructor for checking options, adding default options
     * whatever you want to do.
     */
    public function init()
    {

    }
}