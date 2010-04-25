<?php

namespace PHP\Manipulator\ContainerManipulator;

use PHP\Manipulator\ContainerManipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class UppercaseConstants
extends ContainerManipulator
{

    protected $_isConstant = false;

    protected $_isClassDeclaration = false;

    protected $_isFunctionDeclaration = false;

    protected $_isUse = false;

    protected $_isNamespace = false;

    /**
     *
     * @var TokenContainer
     */
    protected $_container = null;

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

        while ($iterator->valid()) {
            $token = $iterator->current();

            $this->_checkCurrentToken($token);

            if ($this->_isConstantT_STRING($token)) {
                $token->setValue(strtoupper($token->getValue()));
            } elseif($this->_isUse === false && $this->_isNamespace === false) {
                
                if ($this->_isClassMethodAccess($token)) {
                        $token->setValue(strtoupper($token->getValue()));
                } elseif ($this->evaluateConstraint('IsType', $token, T_STRING)) {
                    // only if it is not class-name, function/method-name
                    if (!$this->_isFollowedByDoubleColon($token) && !$this->_isFollowedByOpeningBrace($token) && false === $this->_isFunctionDeclaration && false === $this->_isClassDeclaration) {
                        $token->setValue(strtoupper($token->getValue()));
                    }
//                    } elseif($this->_isFollowedBySemicolon($token) && false === $this->_isFunctionDeclaration && false === $this->_isClassDeclaration) {
//                        $token->setValue(strtoupper($token->getValue()));
//                    } elseif($this->_isFollowedByComma($token) && false === $this->_isFunctionDeclaration && false === $this->_isClassDeclaration) {
//                        $token->setValue(strtoupper($token->getValue()));
//                    } elseif($this->_isFollowedByClosingBrace($token) && false === $this->_isFunctionDeclaration && false === $this->_isClassDeclaration) {
//                        $token->setValue(strtoupper($token->getValue()));
//                    }else {
////                        if ($token->getValue() === 'php_eol') {
////                            var_dump($token);
////                        }
//                    }
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }

//    protected function _isFollowedByComma(Token $token)
//    {
//        $next = $this->_container->getNextToken($token);
//        return (null !== $next && $this->evaluateConstraint('IsType', $next, null) && ',' === $next->getValue());
//    }

//    protected function _isFollowedBySemicolon(Token $token)
//    {
//        $next = $this->_container->getNextToken($token);
//        return (null !== $next && $this->evaluateConstraint('IsType', $next, null) && ';' === $next->getValue());
//    }

    protected function _isFollowedByDoubleColon(Token $token)
    {
        $next = $this->_container->getNextToken($token);
        return (null !== $next && $this->evaluateConstraint('IsType', $next, T_DOUBLE_COLON));
    }

    protected function _isFollowedByOpeningBrace(Token $token)
    {
        $next = $this->_container->getNextToken($token);
        return (null !== $next && $this->evaluateConstraint('IsOpeningBrace', $next, null));
    }

//    protected function _isFollowedByClosingBrace(Token $token)
//    {
//        $next = $this->_container->getNextToken($token);
//        return (null !== $next && $this->evaluateConstraint('IsClosingBrace', $next, null));
//    }

    protected function _isClassMethodAccess(Token $token)
    {
        return $this->_isStaticClassAccess($token) && $this->_isNoStaticMethodCall($token);
    }

    protected function _isNoStaticMethodCall(Token $token)
    {
        $next = $this->_container->getNextToken($token);
        return (null !== $next && $this->evaluateConstraint('IsType', $next, null) && '(' !== $next->getValue());
    }

    protected function _isStaticClassAccess(Token $token)
    {
        $previous = $this->_container->getPreviousToken($token);
        return (null !== $previous && $this->evaluateConstraint('IsType', $previous, T_DOUBLE_COLON));
    }


    protected function _isConstantT_STRING(Token $token)
    {
        return $this->_isConstantDeclaration($token);
    }

    protected function _isConstantDeclaration(Token $token)
    {
        return (true === $this->_isConstant && $this->evaluateConstraint('IsType', $token, T_STRING));
    }

    protected function _checkCurrentToken(Token $token)
    {
        if($this->evaluateConstraint('IsType', $token, T_CONST)) {
            $this->_isConstant = true;
        }

        if($this->evaluateConstraint('IsType', $token, T_CLASS)) {
            $this->_isClassDeclaration = true;
        }

        if (true === $this->_isClassDeclaration && '(' == $token->getValue()) {
            $this->_isClassDeclaration = false;
        }

        if($this->evaluateConstraint('IsType', $token, T_FUNCTION)) {
            $this->_isFunctionDeclaration = true;
        }

        if (true === $this->_isFunctionDeclaration && '(' == $token->getValue()) {
            $this->_isFunctionDeclaration = false;
        }

        if (true === $this->_isConstant && ';' === $token->getValue()) {
            $this->_isConstant = false;
        }

        if($this->evaluateConstraint('IsType', $token, T_USE)) {
            $this->_isUse = true;
        }
        if (true === $this->_isUse && ';' === $token->getValue()) {
            $this->_isUse = false;
        }


        if($this->evaluateConstraint('IsType', $token,\T_NAMESPACE)) {
            $this->_isNamespace = true;
        }

        if (true === $this->_isNamespace && (';' === $token->getValue() || '{' === $token->getValue())) {
            $this->_isNamespace = false;
        }
    }
}
