<?php

namespace PHP\Manipulator;

use Closure;
use PHP\Manipulator\Exception\HelperException;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;
use PHP\Manipulator\TokenFinder\Result;

/**
 * @todo ugly name
 */
class AHelper
{
    /**
     * Load/Instantiate/Run a TokenManipulator on a Token
     *
     * @param TokenManipulator|string $manipulator
     * @param Token                   $token
     * @param mixed                   $params
     */
    public function manipulateToken($manipulator, Token $token, $params = null)
    {
        $manipulator = $this->createClassInstance($manipulator);

        if (!$manipulator instanceof TokenManipulator) {
            $type    = is_object($manipulator) ? get_class($manipulator) : gettype($manipulator);
            $message = sprintf('Manipulator is not instance of PHP\\Manipulator\\TokenManipulator, got "%s"', $type);
            throw new HelperException($message, HelperException::MANIPULATOR_IS_NOT_INSTANCE_OF_TOKEN_MANIPULATOR);
        }

        $manipulator->manipulate($token, $params);
    }

    /**
     * Load/Instantiate/Run a ContainManipulator on a Container
     *
     * @param Action|string  $action
     * @param TokenContainer $container
     */
    public function runAction($action, TokenContainer $container)
    {
        $action = $this->createClassInstance($action);

        if (!$action instanceof Action) {
            $type    = is_object($action) ? get_class($action) : gettype($action);
            $message = sprintf('Action is not instance of PHP\\Manipulator\\Action, got "%s"', $type);
            throw new HelperException($message, HelperException::ACTION_IS_NOT_INSTANCE_OF_ACTION);
        }

        $action->run($container);
    }

    /**
     * Searches a Tokencontainer starting from a Token and returns a Result-Set
     *
     * @param TokenFinder|string $finder
     * @param Token              $token
     * @param TokenContainer     $container
     * @param mixed              $params
     *
     * @return Result
     */
    public function findTokens($finder, Token $token, TokenContainer $container, $params = null)
    {
        $finder = $this->createClassInstance($finder);

        if (!$finder instanceof TokenFinder) {
            $type    = is_object($finder) ? get_class($finder) : gettype($finder);
            $message = sprintf('Finder is not instance of PHP\\Manipulator\\TokenFinder, got "%s"', $type);
            throw new HelperException($message, HelperException::FINDER_IS_NOT_INSTANCE_OF_TOKENFINDER);
        }

        return $finder->find($token, $container, $params);
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param int|int[]              $type
     * @param int[]                  $allowedTypes
     *
     * @return bool
     */
    public function isFollowedByTokenType(
        TokenContainerIterator $iterator,
        $type,
        array $allowedTypes = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]
    ) {
        return $this->isFollowedByTokenMatchedByClosure(
            $iterator,
            MatcherFactory::createIsTypeMatcher($type),
            $allowedTypes
        );
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param int|int[]              $type
     * @param string[]               $allowedTypes
     *
     * @return bool
     */
    public function isPrecededByTokenType(
        TokenContainerIterator $iterator,
        $type,
        array $allowedTypes = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]
    ) {
        return $this->isPrecededByTokenMatchedByClosure(
            $iterator,
            MatcherFactory::createIsTypeMatcher($type),
            $allowedTypes
        );
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param string                 $value
     * @param string[]               $allowedTypes
     *
     * @return bool
     */
    public function isFollowedByTokenValue(
        TokenContainerIterator $iterator,
        $value,
        array $allowedTypes = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]
    ) {
        return $this->isFollowedByTokenMatchedByClosure(
            $iterator,
            MatcherFactory::createHasValueMatcher($value),
            $allowedTypes
        );
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param string                 $value
     * @param string[]               $allowedTypes
     *
     * @return bool
     */
    public function isPrecededByTokenValue(
        TokenContainerIterator $iterator,
        $value,
        array $allowedTypes = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]
    ) {
        return $this->isPrecededByTokenMatchedByClosure(
            $iterator,
            MatcherFactory::createHasValueMatcher($value),
            $allowedTypes
        );
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param Closure                $closure
     * @param string[]               $allowedTypes
     *
     * @return bool
     *
     * @todo ugly name
     */
    public function isFollowedByTokenMatchedByClosure(
        TokenContainerIterator $iterator,
        Closure $closure,
        array $allowedTypes = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]
    ) {
        return $this->isFollowed(
            $iterator,
            $closure,
            MatcherFactory::createIsTypeMatcher($allowedTypes)
        );
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param Closure                $isAllowedTokenMatcher
     * @param string[]               $allowedTypes
     *
     * @return bool
     *
     * @todo ugly name
     */
    public function isPrecededByTokenMatchedByClosure(
        TokenContainerIterator $iterator,
        Closure $isAllowedTokenMatcher,
        array $allowedTypes = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT]
    ) {
        return $this->isPreceded(
            $iterator,
            $isAllowedTokenMatcher,
            MatcherFactory::createIsTypeMatcher($allowedTypes)
        );
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param Closure                $isSearchedToken
     * @param Closure                $isAllowedToken
     * @param Token                  $found
     *
     * @return bool
     */
    public function isPreceded(
        TokenContainerIterator $iterator,
        Closure $isSearchedToken,
        Closure $isAllowedToken,
        Token &$found = null
    ) {
        $token  = $iterator->current();
        $result = false;
        $iterator->previous();

        while ($iterator->valid()) {
            $currentToken = $iterator->current();
            if ($isSearchedToken($currentToken)) {
                $found  = $currentToken;
                $result = true;
                break;
            }
            if (!$isAllowedToken($currentToken)) {
                break;
            }
            $iterator->previous();
        }

        $iterator->seekToToken($token);

        return $result;
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param Closure                $isSearchedToken
     * @param Closure                $isAllowedToken
     * @param Token                  $found
     *
     * @return bool
     */
    public function isFollowed(
        TokenContainerIterator $iterator,
        Closure $isSearchedToken,
        Closure $isAllowedToken,
        Token &$found = null
    ) {
        $token  = $iterator->current();
        $result = false;
        $iterator->next();

        while ($iterator->valid()) {
            $currentToken = $iterator->current();
            if ($isSearchedToken($currentToken)) {
                $found  = $currentToken;
                $result = true;
                break;
            }
            if (!$isAllowedToken($currentToken)) {
                break;
            }
            $iterator->next();
        }

        $iterator->seekToToken($token);

        return $result;
    }

    /**
     * @param TokenContainerIterator $iterator
     *
     * @return Token
     */
    public function getMatchingBrace(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $brace = $token->getValue();

        $searchForward = true;
        switch ($brace) {
            case '[':
                $incrementBrace = '[';
                $decrementBrace = ']';
                break;
            case ']':
                $incrementBrace = ']';
                $decrementBrace = '[';
                $searchForward  = false;
                break;
            case '(':
                $incrementBrace = '(';
                $decrementBrace = ')';
                break;
            case ')':
                $incrementBrace = ')';
                $decrementBrace = '(';
                $searchForward  = false;
                break;
            case '{':
                $incrementBrace = '{';
                $decrementBrace = '}';
                break;
            case '}':
                $incrementBrace = '}';
                $decrementBrace = '{';
                $searchForward  = false;
                break;
            default:
                $message = sprintf('Token is no brace like (,),{,},[ or ], got value "%s"', $brace);
                throw new HelperException($message, HelperException::UNSUPPORTED_BRACE_EXCEPTION);
        }

        $level = 1;
        $this->nextToken($iterator, $searchForward);
        $foundToken = null;
        while ($iterator->valid()) {
            $currentToken = $iterator->current();
            if (null === $currentToken->getType()) {
                if ($currentToken->getValue() === $incrementBrace) {
                    $level++;
                } elseif ($currentToken->getValue() === $decrementBrace) {
                    $level--;
                }
            }
            if ($level === 0) {
                $foundToken = $currentToken;
                break;
            }
            $this->nextToken($iterator, $searchForward);
        }
        $iterator->update($token);

        return $foundToken;
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param bool                   $forward
     */
    private function nextToken(TokenContainerIterator $iterator, $forward = true)
    {
        if ($forward) {
            $iterator->next();
        } else {
            $iterator->previous();
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     * @param Closure                $closure
     *
     * @return Token
     */
    public function getNextMatchingToken(TokenContainerIterator $iterator, Closure $closure)
    {
        $token = $iterator->current();

        $foundToken = null;
        while ($iterator->valid()) {
            $currentToken = $iterator->current();
            if ($closure($currentToken)) {
                $foundToken = $currentToken;
                break;
            }
            $iterator->next();
        }
        $iterator->update($token);

        return $foundToken;
    }

    /**
     * @param string|object $object
     *
     * @return object
     */
    private function createClassInstance($object)
    {
        if (is_string($object)) {
            return new $object();
        }

        return $object;
    }
}
