<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\Config;
use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenManipulator;

// @todo better name ?
// @todo extend helper-methods to use config
abstract class AHelper
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
     * Load/Instantiate/Evaluate Token Constraint on a Token
     *
     * @param \PHP\Manipulator\TokenConstraint|string $constraint
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function evaluateConstraint($constraint, Token $token, $params = null, $autoPrefix = true)
    {
        $constraint = $this->getClassInstance($constraint, 'PHP\Manipulator\TokenConstraint\\', $autoPrefix);
        if (!$constraint instanceof \PHP\Manipulator\TokenConstraint) {
            $message = 'constraint is not instance of \PHP\Manipulator\TokenConstraint';
            throw new \Exception($message);
        }
        /* @var $constraint \PHP\Manipulator\TokenConstraint */
        return $constraint->evaluate($token, $params);
    }

    /**
     * Load/Instantiate/Evaluate Container Constraint on a Container
     *
     * @param \PHP\Manipulator\ContainerConstraint|string $constraint
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return boolean
     */
    public function evaluateContainerConstraint($constraint, TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $constraint = $this->getClassInstance($constraint, 'PHP\Manipulator\ContainerConstraint\\', $autoPrefix);
        if (!$constraint instanceof \PHP\Manipulator\ContainerConstraint) {
            $message = 'constraint is not instance of \PHP\Manipulator\ContainerConstraint';
            throw new \Exception($message);
        }
        /* @var $constraint \PHP\Manipulator\ContainerConstraint */
        return $constraint->evaluate($container, $params);
    }

    /**
     * Load/Instantiate/Run a TokenManipulator on a Token
     *
     * @param \PHP\Manipulator\TokenManipulator $manipulator
     * @param \PHP\Manipulator\Token $token
     * @param mixed $params
     * @param boolean $autoPrefix
     */
    public function manipulateToken($manipulator, Token $token, $params = null, $autoPrefix = true)
    {
        $manipulator = $this->getClassInstance($manipulator, 'PHP\Manipulator\TokenManipulator\\', $autoPrefix);

        if (!$manipulator instanceof \PHP\Manipulator\TokenManipulator) {
            $message = 'manipulator is not instance of \PHP\Manipulator\TokenManipulator';
            throw new \Exception($message);
        }
        /* @var $manipulator \PHP\Manipulator\TokenManipulator */
        $manipulator->manipulate($token, $params);
    }

    /**
     * Load/Instantiate/Run a ContainManipulator on a Container
     *
     * @param \PHP\Manipulator\ContainerManipulator|string $manipulator
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     */
    public function manipulateContainer($manipulator, TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $manipulator = $this->getClassInstance($manipulator, 'PHP\Manipulator\ContainerManipulator\\', $autoPrefix);

        if (!$manipulator instanceof ContainerManipulator) {
            $message = 'manipulator is not instance of \PHP\Manipulator\ContainerManipulator';
            throw new \Exception($message);
        }

        /* @var $manipulator  \PHP\Manipulator\ContainerManipulator */
        $manipulator->manipulate($container, $params);
    }

    /**
     *
     * @param \PHP\Manipulator\TokenFinder|string $finder
     * @param \PHP\Manipulator\Token $token
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     * @return \PHP\Manipulator\TokenFinder\Result
     */
    public function findTokens($finder, Token $token, TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $finder = $this->getClassInstance($finder, 'PHP\Manipulator\TokenFinder\\', $autoPrefix);

        if (!$finder instanceof \PHP\Manipulator\TokenFinder) {
            $message = 'finder is not instance of \PHP\Manipulator\TokenFinder';
            throw new \Exception($message);
        }

        /* @var $finder \PHP\Manipulator\TokenFinder */
        return $finder->find($token, $container, $params);
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