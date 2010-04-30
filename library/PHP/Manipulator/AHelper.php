<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use PHP\Manipulator\Config;
use PHP\Manipulator\ContainerConstraint;
use PHP\Manipulator\Action;
use PHP\Manipulator\TokenConstraint;
use PHP\Manipulator\TokenFinder;
use PHP\Manipulator\TokenManipulator;

// @todo better name ?
// @todo extend helper-methods to use config
// @todo caching for getClassInstance-Calls ?
abstract class AHelper
{

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
     * @param \PHP\Manipulator\Action|string $manipulator
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     * @param boolean $autoPrefix
     */
    public function manipulateContainer($action, TokenContainer $container, $params = null, $autoPrefix = true)
    {
        $action = $this->getClassInstance($action, 'PHP\Manipulator\Action\\', $autoPrefix);

        if (!$action instanceof Action) {
            $message = 'manipulator is not instance of \PHP\Manipulator\Action';
            throw new \Exception($message);
        }

        /* @var $manipulator  \PHP\Manipulator\Action */
        $action->run($container, $params);
    }

    /**
     * Searches a Tokencontainer starting from a Token and returns a Result-Set
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
     * @todo a cache which creates only one instance of each Constraint and Manipulator and uses it again and again.
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
}