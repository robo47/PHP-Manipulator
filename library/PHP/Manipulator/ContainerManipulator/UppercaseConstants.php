<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

// @todo Extend to support whitespace-comments after some tokens
// @todo Think about some kind of Token+Container-Constraint which can check if a token is followed or preceded by something ...
class UppercaseConstants
extends ContainerManipulator
{

    /**
     * @var boolean
     */
    protected $_isConstant = false;

    /**
     * @var boolean
     */
    protected $_isClassDeclaration = false;

    /**
     * @var boolean
     */
    protected $_isFunctionDeclaration = false;

    /**
     * @var boolean
     */
    protected $_isUse = false;

    /**
     * @var boolean
     */
    protected $_isNamespace = false;

    /**
     * @var TokenContainer
     */
    protected $_container = null;

    /**
     * @var Token
     */
    protected $_next = null;

    /**
     * Manipulate
     *
     * @param PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();
        $this->_container = $container;

        $this->_setNext($iterator);
        while ($iterator->valid()) {
            $token = $iterator->current();

            $this->_checkCurrentToken($token);

            if ($this->_isConstant($token)) {
                $token->setValue(strtoupper($token->getValue()));
            }
            $this->_setNext($iterator);
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param \PHP\Manipulator\TokenContainerIterator $iterator
     */
    protected function _setNext(TokenContainer\Iterator $iterator)
    {
        $iterator->next();
        $iterator->next();
        if ($iterator->valid()) {
            $this->_next = $iterator->current();
        } else {
            $this->_next = null;
        }
        $iterator->previous();
        $iterator->previous();
    }

    /**
     * @var boolean
     */
    protected function _notInsideClassFunctionMethodUseOrNamespace()
    {
        return (false === $this->_isUse &&
                false === $this->_isNamespace &&
                false === $this->_isFunctionDeclaration &&
                false === $this->_isClassDeclaration);
    }

    /**
     * @param Token $token
     * @var boolean
     */
    protected function _isConstant(Token $token)
    {
        return $this->evaluateConstraint('IsType', $token, T_STRING) &&
               ( (true === $this->_isConstant) ||
                 ($this->_notInsideClassFunctionMethodUseOrNamespace() && !$this->_isFollowedByDoubleColon($token) && !$this->_isFollowedByOpeningBrace($token)));
    }

    /**
     * @param Token $token
     * @var boolean
     */
    protected function _isFollowedByDoubleColon(Token $token)
    {
        $next = $this->_next;
        return (null !== $next && $this->evaluateConstraint('IsType', $next, T_DOUBLE_COLON));
    }

    /**
     * @param Token $token
     * @var boolean
     */
    protected function _isFollowedByOpeningBrace(Token $token)
    {
        $next = $this->_next;
        return (null !== $next && $this->evaluateConstraint('IsOpeningBrace', $next));
    }

    /**
     * @param Token $token
     * @var boolean
     */
    protected function _isNotAMethodCall(Token $token)
    {
        $next = $this->_next;
        return (null !== $next && !$this->evaluateConstraint('IsOpeningBrace', $next));
    }

    /**
     * Checks the current token and sets internal flags to true or false
     *
     * @param Token $token
     */
    protected function _checkCurrentToken(Token $token)
    {
        if($this->evaluateConstraint('IsType', $token, T_CONST)) {
            $this->_isConstant = true;
        } elseif($this->evaluateConstraint('IsType', $token, T_USE)) {
            $this->_isUse = true;
        } elseif($this->evaluateConstraint('IsType', $token, T_NAMESPACE)) {
            $this->_isNamespace = true;
        } elseif($this->evaluateConstraint('IsType', $token, T_CLASS)) {
            $this->_isClassDeclaration = true;
        } elseif($this->evaluateConstraint('IsType', $token, T_FUNCTION)) {
            $this->_isFunctionDeclaration = true;
        }

        if ($this->_isSemicolon($token)) {
            if (true === $this->_isConstant) {
                $this->_isConstant = false;
            }
            if (true === $this->_isUse) {
                $this->_isUse = false;
            }
        }

        if ($this->evaluateConstraint('IsOpeningBrace', $token)) {
            if (true === $this->_isClassDeclaration) {
                $this->_isClassDeclaration = false;
            }
            if (true === $this->_isFunctionDeclaration) {
                $this->_isFunctionDeclaration = false;
            }
        }

        if (true === $this->_isNamespace && ($this->_isSemicolon($token) || $this->evaluateConstraint('IsClosingCurlyBrace', $token))) {
            $this->_isNamespace = false;
        }
    }

    /**
     * @param Token $token
     * @return boolean
     * @todo Create IsSemicolonConstraint
     */
    protected function _isSemicolon(Token $token)
    {
        return ';' === $token->getValue();
    }
}
