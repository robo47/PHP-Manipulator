<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\Iterator;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class UppercaseConstants
extends Action
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
     * Make all Constants uppercase
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $this->_container = $container;

        $this->_setNext($iterator);
        while ($iterator->valid()) {
            $token = $iterator->current();

            $this->_checkCurrentToken($token);

            if ($this->_isConstant($iterator)) {
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
     * @param \PHP\Manipulator\Token $token
     * @var boolean
     */
    protected function _isConstant(Iterator $iterator)
    {
        return $this->isType($iterator->current(), T_STRING) &&
        ( (true === $this->_isConstant) ||
            ($this->_notInsideClassFunctionMethodUseOrNamespace() &&
             !$this->isFollowedByTokenValue($iterator, '::') &&
             !$this->isFollowedByTokenValue($iterator, '(')));
    }


    /**
     * @param \PHP\Manipulator\Token $token
     * @var boolean
     */
    protected function _isNotAMethodCall(Token $token)
    {
        $next = $this->_next;
        return (null !== $next && !$this->isOpeningBrace($next));
    }

    /**
     * Checks the current token and sets internal flags to true or false
     *
     * @param \PHP\Manipulator\Token $token
     */
    protected function _checkCurrentToken(Token $token)
    {
        if ($this->isType($token, T_CONST)) {
            $this->_isConstant = true;
        } else if ($this->isType($token, T_USE)) {
            $this->_isUse = true;
        } else if ($this->isType($token, T_NAMESPACE)) {
            $this->_isNamespace = true;
        } else if ($this->isType($token, T_CLASS)) {
            $this->_isClassDeclaration = true;
        } else if ($this->isType($token, T_FUNCTION)) {
            $this->_isFunctionDeclaration = true;
        } if ($this->isSemicolon( $token)) {
            if (true === $this->_isConstant) {
                $this->_isConstant = false;
            }
            if (true === $this->_isUse) {
                $this->_isUse = false;
            }
        } else if ($this->isOpeningBrace($token)) {
            if (true === $this->_isClassDeclaration) {
                $this->_isClassDeclaration = false;
            }
            if (true === $this->_isFunctionDeclaration) {
                $this->_isFunctionDeclaration = false;
            }
        }

        if (true === $this->_isNamespace &&
            ($this->isSemicolon($token) || $this->isClosingCurlyBrace($token))) {
            $this->_isNamespace = false;
        }
    }
}