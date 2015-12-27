<?php

namespace PHP\Manipulator\TokenFinder;

use PHP\Manipulator\Exception\TokenFinderException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;
use PHP\Manipulator\TokenFinder;

class FunctionFinder extends TokenFinder
{
    const PARAM_INCLUDE_PHPDOC            = 'includePhpdoc';
    const PARAM_INCLUDE_METHOD_PROPERTIES = 'includeMethodProperties';

    /**
     * @var bool
     */
    private $inside = false;

    /**
     * @var int
     */
    private $level = 0;

    /**
     * @var bool
     */
    private $end = false;

    /**
     * Finds tokens
     *
     * @param Token          $token
     * @param TokenContainer $container
     * @param mixed          $params
     *
     * @return Result
     */
    public function find(Token $token, TokenContainer $container, $params = null)
    {
        if (!$token->isType(T_FUNCTION)) {
            $message = 'Starttoken is not T_FUNCTION';
            throw new TokenFinderException($message, TokenFinderException::UNSUPPORTED_START_TOKEN);
        }

        $iterator = $container->getIterator();
        $iterator->seekToToken($token);

        if ($this->includeMethodProperties($params) &&
            !$this->includePhpDoc($params)
        ) {
            $this->seekToMethodProperties($iterator);
        }

        if ($this->includePhpDoc($params)) {
            $this->seekToPhpdoc($iterator);
        }
        $this->inside = false;
        $this->level  = 0;
        $this->end    = false;

        $result = new Result();
        while ($iterator->valid() && false === $this->end) {
            $result->addToken($iterator->current());

            $this->checkLevel($iterator);
            $this->checkBreak($iterator);

            $iterator->next();
        }

        return $result;
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function checkBreak(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        // abstract methods or interface-methods
        if (false === $this->inside && $token->isSemicolon()) {
            $this->end = true;
        }
        // last curly-brace closed
        if (true === $this->inside && $this->level === 0) {
            $this->end = true;
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function checkLevel(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        if ($token->isOpeningCurlyBrace()) {
            $this->inside = true;
            $this->level++;
        }

        if ($token->isClosingCurlyBrace()) {
            $this->level--;
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function seekToMethodProperties(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        $iterator->previous();
        while ($iterator->valid()) {
            if (!$iterator->current()->isType([
                T_WHITESPACE,
                T_PUBLIC,
                T_COMMENT,
                T_DOC_COMMENT,
                T_PUBLIC,
                T_PROTECTED,
                T_PRIVATE,
                T_STATIC,
                T_ABSTRACT,
            ])
            ) {
                $iterator->next();
                while ($iterator->valid()) {
                    if (!$iterator->current()->isType(
                        [T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_FUNCTION, T_ABSTRACT]
                    )
                    ) {
                        $iterator->next();
                    } else {
                        break;
                    }
                }
                break;
            }
            $iterator->previous();
        }
        // didn't find anything
        if (!$iterator->valid()) {
            $iterator->seekToToken($token);
        }
    }

    /**
     * @param TokenContainerIterator $iterator
     */
    private function seekToPhpdoc(TokenContainerIterator $iterator)
    {
        $token = $iterator->current();
        // travel reverse as long as there is only whitespace and stuff
        $iterator->previous();
        while ($iterator->valid()) {
            if (!$iterator->current()->isType(
                [T_WHITESPACE, T_PUBLIC, T_COMMENT, T_DOC_COMMENT, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC]
            )
            ) {
                $iterator->next();
                while ($iterator->valid()) {
                    if (!$iterator->current()->isType(
                        [T_DOC_COMMENT, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC, T_FUNCTION]
                    )
                    ) {
                        $iterator->next();
                    } else {
                        break;
                    }
                }
                break;
            }
            $iterator->previous();
        }
        // didn't find anything
        if (!$iterator->valid()) {
            $iterator->seekToToken($token);
        }
    }

    /**
     * @param array|null $params
     *
     * @return bool
     */
    private function includePhpDoc($params)
    {
        if (is_array($params) && isset($params[self::PARAM_INCLUDE_PHPDOC])) {
            return (bool) $params[self::PARAM_INCLUDE_PHPDOC];
        }

        return false;
    }

    /**
     * wheter to check for public/protected/private
     *
     * @param array|null $params
     *
     * @return bool
     */
    private function includeMethodProperties($params)
    {
        if (is_array($params) && isset($params[self::PARAM_INCLUDE_METHOD_PROPERTIES])) {
            return (bool) $params[self::PARAM_INCLUDE_METHOD_PROPERTIES];
        }

        return false;
    }
}
