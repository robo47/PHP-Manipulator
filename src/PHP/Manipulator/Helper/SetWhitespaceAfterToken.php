<?php

namespace PHP\Manipulator\Helper;

use PHP\Manipulator\AHelper;
use PHP\Manipulator\Exception\HelperException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;

class SetWhitespaceAfterToken extends AHelper
{
    /**
     * @var TokenContainer
     */
    protected $container;

    /**
     * @param TokenContainer $container
     * @param Token[]        $tokens
     * @param string[]       $whitespace
     */
    public function run(TokenContainer $container, array $tokens, array $whitespace)
    {
        $this->container = $container;
        $iterator        = $container->getIterator();

        while ($iterator->valid()) {
            if (in_array($iterator->current(), $tokens, true)) {
                $this->setWhitespace($iterator, $whitespace);
            }
            $iterator->next();
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param string[]               $whitespace
     */
    private function setWhitespace(TokenContainerIterator $iterator, array $whitespace)
    {
        $token = $iterator->current();
        $this->moveIteratorToTargetToken($iterator);
        $targetToken = $iterator->current();

        $tokenValue = $this->getWhitespaceForToken($token, $whitespace);

        $containerChanger = false;
        if (null !== $targetToken) {
            if ($targetToken->isWhitespace()) {
                if (empty($tokenValue)) {
                    $this->container->removeToken($targetToken);
                    $containerChanger = true;
                } else {
                    $targetToken->setValue($tokenValue);
                }
            } else {
                if (!empty($tokenValue)) {
                    $newToken = Token::createFromMixed([T_WHITESPACE, $tokenValue]);
                    $this->insertToken($newToken, $iterator);
                    $containerChanger = true;
                }
            }
        }
        $this->moveIteratorBackFromTagetToken($iterator);
        if (true === $containerChanger) {
            $iterator->update($iterator->current());
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    protected function moveIteratorToTargetToken(TokenContainerIterator $iterator)
    {
        $iterator->next();
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    protected function moveIteratorBackFromTagetToken(TokenContainerIterator $iterator)
    {
        $iterator->previous();
    }

    /**
     * @param Token                  $newToken
     * @param TokenContainerIterator $iterator
     */
    protected function insertToken(Token $newToken, TokenContainerIterator $iterator)
    {
        $this->container->insertTokenBefore($iterator->current(), $newToken);
    }

    /**
     * @param Token    $token
     * @param string[] $whitespaces
     *
     * @return mixed
     */
    private function getWhitespaceForToken(Token $token, array $whitespaces)
    {
        if (null === $token->getType()) {
            $tokenval = $token->getValue();
        } else {
            $tokenval = $token->getType();
        }
        if (array_key_exists($tokenval, $whitespaces)) {
            return $whitespaces[$tokenval];
        }
        $message = sprintf('No option found for: %s (%s)', $token->getTokenName(), $tokenval);
        throw new HelperException($message, HelperException::OPTION_NOT_FOUND);
    }
}
