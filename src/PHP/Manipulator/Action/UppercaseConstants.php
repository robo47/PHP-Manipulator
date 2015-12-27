<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;

class UppercaseConstants extends Action
{
    /**
     * @var bool
     */
    private $isConstant = false;

    /**
     * @var bool
     */
    private $isClassDeclaration = false;

    /**
     * @var bool
     */
    private $isFunctionDeclaration = false;

    /**
     * @var bool
     */
    private $isUse = false;

    /**
     * @var bool
     */
    private $isNamespace = false;

    /**
     * @var TokenContainer
     */
    private $container;

    /**
     * @var Token
     */
    private $next;

    public function run(TokenContainer $container)
    {
        $iterator        = $container->getIterator();
        $this->container = $container;

        $this->setNext($iterator);
        while ($iterator->valid()) {
            $token = $iterator->current();

            $this->checkCurrentToken($token);

            if ($this->isConstant($iterator)) {
                $token->setValue(strtoupper($token->getValue()));
            }
            $this->setNext($iterator);
            $iterator->next();
        }
        $container->retokenize();
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function setNext(TokenContainerIterator $iterator)
    {
        $iterator->next();
        $iterator->next();
        if ($iterator->valid()) {
            $this->next = $iterator->current();
        } else {
            $this->next = null;
        }
        $iterator->previous();
        $iterator->previous();
    }

    /**
     * @return bool
     */
    private function notInsideClassFunctionMethodUseOrNamespace()
    {
        return (false === $this->isUse &&
            false === $this->isNamespace &&
            false === $this->isFunctionDeclaration &&
            false === $this->isClassDeclaration);
    }

    /**
     * @param TokenContainerIterator $iterator
     *
     * @return bool
     */
    private function isConstant(TokenContainerIterator $iterator)
    {
        return $iterator->current()->isType(T_STRING) &&
        ((true === $this->isConstant) ||
            ($this->notInsideClassFunctionMethodUseOrNamespace() &&
                !$this->isFollowedByTokenValue($iterator, '::') &&
                !$this->isFollowedByTokenValue($iterator, '(')));
    }

    /**
     * @param Token $token
     */
    private function checkCurrentToken(Token $token)
    {
        if ($token->isType(T_CONST)) {
            $this->isConstant = true;
        } elseif ($token->isType(T_USE)) {
            $this->isUse = true;
        } elseif ($token->isType(T_NAMESPACE)) {
            $this->isNamespace = true;
        } elseif ($token->isType(T_CLASS)) {
            $this->isClassDeclaration = true;
        } elseif ($token->isType(T_FUNCTION)) {
            $this->isFunctionDeclaration = true;
        }

        if ($token->isSemicolon()) {
            if (true === $this->isConstant) {
                $this->isConstant = false;
            }
            if (true === $this->isUse) {
                $this->isUse = false;
            }
        } elseif ($token->isOpeningBrace()) {
            if (true === $this->isClassDeclaration) {
                $this->isClassDeclaration = false;
            }
            if (true === $this->isFunctionDeclaration) {
                $this->isFunctionDeclaration = false;
            }
        }

        if (true === $this->isNamespace &&
            ($token->isSemicolon() || $token->isClosingCurlyBrace())
        ) {
            $this->isNamespace = false;
        }
    }
}
