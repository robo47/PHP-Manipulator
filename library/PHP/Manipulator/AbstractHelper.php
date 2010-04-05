<?php

abstract class PHP_Manipulator_AbstractHelper
{

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
     * @return PHP_Manipulator_Rule_Abstract *Provides Fluent Interface*
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
     * @return PHP_Manipulator_Rule_Abstract *Provides Fluent Interface*
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
            throw new PHP_Manipulator_Exception($message);
        }
        return $this->_options[$option];
    }

    /**
     * Load/Instantiate/Evaluate Token Constraint on a Token
     *
     * @param PHP_Manipulator_TokenConstraint_Interface|string $constraint
     * @param PHP_Manipulator_Token $token
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function evaluateConstraint($constraint, PHP_Manipulator_Token $token, $params = null, $autoPrefix = true)
    {
        $constraint = $this->getClassInstance($constraint, 'PHP_Manipulator_TokenConstraint_', $autoPrefix);
        if (!$constraint instanceof PHP_Manipulator_TokenConstraint_Interface) {
            $message = 'constraint is not instance of PHP_Manipulator_TokenConstraint_Interface';
            throw new PHP_Manipulator_Exception($message);
        }
        /* @var $constraint PHP_Manipulator_TokenConstraint_Interface */
        return $constraint->evaluate($token, $params);
    }

    /**
     * Load/Instantiate/Evaluate Container Constraint on a Container
     *
     * @param PHP_Manipulator_ContainerConstraint_Interface|string $constraint
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function evaluateContainerConstraint($constraint, PHP_Manipulator_TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $constraint = $this->getClassInstance($constraint, 'PHP_Manipulator_ContainerConstraint_', $autoPrefix);
        if (!$constraint instanceof PHP_Manipulator_ContainerConstraint_Interface) {
            $message = 'constraint is not instance of PHP_Manipulator_ContainerConstraint_Interface';
            throw new PHP_Manipulator_Exception($message);
        }
        /* @var $constraint PHP_Manipulator_ContainerConstraint_Interface */
        return $constraint->evaluate($container, $params);
    }

    /**
     * Load/Instantiate/Run a TokenManipulator on a Token
     *
     * @param PHP_Manipulator_TokenManipulator_Interface $manipulator
     * @param PHP_Manipulator_Token $token
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function manipulateToken($manipulator, PHP_Manipulator_Token $token, $params = null, $autoPrefix = true)
    {
        $manipulator = $this->getClassInstance($manipulator, 'PHP_Manipulator_TokenManipulator_', $autoPrefix);

        if (!$manipulator instanceof PHP_Manipulator_TokenManipulator_Interface) {
            $message = 'manipulator is not instance of PHP_Manipulator_TokenManipulator_Interface';
            throw new PHP_Manipulator_Exception($message);
        }
        /* @var $manipulator PHP_Manipulator_TokenManipulator_Interface */
        return $manipulator->manipulate($token, $params);
    }

    /**
     * Load/Instantiate/Run a ContainManipulator on a Container
     *
     * @param PHP_Manipulator_ContainerManipulator_Interface $manipulator
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function manipulateContainer($manipulator, PHP_Manipulator_TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $manipulator = $this->getClassInstance($manipulator, 'PHP_Manipulator_ContainerManipulator_', $autoPrefix);

        if (!$manipulator instanceof PHP_Manipulator_ContainerManipulator_Interface) {
            $message = 'manipulator is not instance of PHP_Manipulator_ContainerManipulator_Interface';
            throw new PHP_Manipulator_Exception($message);
        }
        /* @var $manipulator PHP_Manipulator_ContainerManipulator_Interface */
        return $manipulator->manipulate($container, $params);
    }

    /**
     * Get class instance
     *
     * @param string $class
     * @param string $prefix
     * @param boolean $autoPrefix
     * @return object
     */
    public function getClassInstance($class, $prefix, $autoPrefix = true)
    {
        if (!is_string($class)) {
            return $class;
        }
        $classname = $class;
        if ($autoPrefix) {
            $classname = $prefix . $class;
        }
        return new $classname;
    }

    /**
     * Called from constructor for checking options, adding default options
     * whatever you want to do.
     */
    public function init()
    {
        
    }
}