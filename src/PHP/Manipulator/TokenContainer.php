<?php

namespace PHP\Manipulator;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use PHP\Manipulator\Exception\TokenContainerException;
use PHP\Manipulator\Exception\TokenException;
use PHP\Manipulator\TokenContainer\ReverseTokenContainerIterator;
use PHP\Manipulator\TokenContainer\TokenContainerIterator;

/**
 * @todo do we need the ArrayAccess-Api ? getTokenAtPosition, hasTokenAtPosition
 */
class TokenContainer implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * @param Token[] $tokens
     */
    protected function __construct(array $tokens)
    {
        $this->setTokens($tokens);
    }

    /**
     * @param string|array $mixed
     *
     * @return TokenContainer
     */
    public static function factory($mixed = [])
    {
        if (is_string($mixed)) {
            return self::createFromCode($mixed);
        } elseif (is_array($mixed)) {
            return self::createFromTokenArray($mixed);
        }

        $type    = is_object($mixed) ? get_class($mixed) : gettype($mixed);
        $message = sprintf('Input neither string or array, got "%s"', $type);
        throw new TokenContainerException($message);
    }

    /**
     * @return TokenContainer
     */
    public static function createEmptyContainer()
    {
        return self::createFromTokenArray([]);
    }

    /**
     * @param string $code
     *
     * @return TokenContainer
     */
    public static function createFromCode($code)
    {
        $tokens = token_get_all($code);

        return self::createFromTokenArray($tokens);
    }

    /**
     * @param string[]|Token[] $tokens
     *
     * @return TokenContainer
     */
    public static function createFromTokenArray(array $tokens)
    {
        return new self(self::createTokensFromArray($tokens));
    }

    /**
     * @param array $tokens
     *
     * @throws TokenException
     *
     * @return Token[]
     */
    public static function createTokensFromArray(array $tokens)
    {
        $array = [];
        foreach ($tokens as $token) {
            if (!$token instanceof Token) {
                $token = Token::createFromMixed($token);
            }
            $array[] = $token;
        }

        return $array;
    }

    /**
     * @param int   $offset
     * @param Token $value
     */
    public function offsetSet($offset, $value)
    {
        $this->checkOffsetType($offset);
        $this->checkValueType($value);

        if (null === $offset) {
            $this->tokens[] = $value;
        } else {
            $this->tokens[$offset] = $value;
        }
    }

    /**
     * @param int $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        $this->checkOffsetType($offset);

        return isset($this->tokens[$offset]);
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset)
    {
        $this->checkOffsetType($offset);
        $this->ensureOffsetExists($offset);
        unset($this->tokens[$offset]);
    }

    /**
     * @param int $offset
     *
     * @return Token
     */
    public function offsetGet($offset)
    {
        $this->checkOffsetType($offset);
        $this->ensureOffsetExists($offset);

        return $this->tokens[$offset];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->tokens);
    }

    /**
     * @param int   $offset
     * @param Token $value
     *
     * @return TokenContainer
     */
    public function insertAtOffset($offset, Token $value)
    {
        $position = $this->getPositionForOffset($offset);
        $this->insertAtPosition($position, $value);

        return $this;
    }

    /**
     * @param Token $token
     *
     * @todo make this one throw exception and implement hasPreviousToken
     *
     * @return Token|null
     */
    public function getPreviousToken(Token $token)
    {
        $iterator = $this->getIterator();
        try {
            $iterator->seekToToken($token);
            $iterator->previous();
            if ($iterator->valid()) {
                return $iterator->current();
            } else {
                return null;
            }
        } catch (TokenContainerException $e) {
            return null;
        }
    }

    /**
     * @param Token $token
     *
     * @return Token|null
     */
    public function getNextToken(Token $token)
    {
        $iterator = $this->getIterator();
        try {
            $iterator->seekToToken($token);
            $iterator->next();
            if ($iterator->valid()) {
                return $iterator->current();
            } else {
                return null;
            }
        } catch (TokenContainerException $e) {
            return null;
        }
    }

    /**
     * @param Token $token
     *
     * @return int
     */
    public function getOffsetByToken(Token $token)
    {
        foreach ($this->tokens as $offset => $element) {
            if ($element === $token) {
                return $offset;
            }
        }
        $message = sprintf('Token "%s" does not exist in this container', $token);
        throw new TokenContainerException($message, TokenContainerException::TOKEN_DOES_NOT_EXIST_IN_CONTAINER);
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    public function contains(Token $token)
    {
        $contains = false;
        foreach ($this->tokens as $element) {
            if ($element === $token) {
                $contains = true;
                break;
            }
        }

        return $contains;
    }

    /**
     * @param Token $after
     * @param Token $newToken
     *
     * @return TokenContainer
     */
    public function insertTokenAfter(Token $after, Token $newToken)
    {
        $this->ensureContainerContainsToken($after);

        $offset   = $this->getOffsetByToken($after);
        $position = $this->getPositionForOffset($offset);
        $this->insertAtPosition($position + 1, $newToken);

        return $this;
    }

    /**
     * @param Token $before
     * @param Token $newToken
     *
     * @return TokenContainer
     */
    public function insertTokenBefore(Token $before, Token $newToken)
    {
        $this->ensureContainerContainsToken($before);

        $iterator = $this->getIterator();
        $iterator->seekToToken($before);
        $iterator->previous();
        $position = -1;
        if ($iterator->valid()) {
            $position = $this->getPositionForOffset($iterator->key());
        }
        $this->insertAtPosition($position + 1, $newToken);

        return $this;
    }

    /**
     * @param Token   $before
     * @param Token[] $newTokens
     *
     * @return TokenContainer
     */
    public function insertTokensBefore(Token $before, array $newTokens)
    {
        $this->ensureContainerContainsToken($before);

        $newTokens = array_reverse($newTokens);
        foreach ($newTokens as $newToken) {
            $this->insertTokenBefore($before, $newToken);
            $before = $newToken;
        }

        return $this;
    }

    /**
     * @param Token $after
     * @param array $newTokens
     *
     * @return TokenContainer
     */
    public function insertTokensAfter(Token $after, array $newTokens)
    {
        $this->ensureContainerContainsToken($after);

        foreach ($newTokens as $newToken) {
            $this->insertTokenAfter($after, $newToken);
            $after = $newToken;
        }

        return $this;
    }

    /**
     * @param string $code
     *
     * @return TokenContainer
     */
    public function recreateContainerFromCode($code)
    {
        $tokens = token_get_all($code);
        $this->setTokens(self::createTokensFromArray($tokens));

        return $this;
    }

    /**
     * @return TokenContainer
     */
    public function retokenize()
    {
        $this->recreateContainerFromCode($this->toString());

        return $this;
    }

    /**
     * @param Token[] $tokens
     *
     * @return TokenContainer
     */
    public function removeTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            $this->removeToken($token);
        }

        return $this;
    }

    /**
     * @param Token $token
     *
     * @return TokenContainer
     */
    public function removeToken(Token $token)
    {
        $offset = $this->getOffsetByToken($token);
        unset($this[$offset]);

        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $code = '';
        foreach ($this as $token) {
            /* @var $token Token */
            $code .= (string) $token;
        }

        return $code;
    }

    /**
     * @return Token[]
     */
    public function toArray()
    {
        return $this->tokens;
    }

    /**
     * Removes a sequence of Tokens from a token to another (including start and end-token)
     *
     * @param Token $from
     * @param Token $to
     */
    public function removeTokensFromTo(Token $from, Token $to)
    {
        $this->ensureContainerContainsToken($from);
        $this->ensureContainerContainsToken($to);
        $iterator = $this->getIterator();
        $iterator->seekToToken($from);

        $delete = [];
        while ($iterator->valid()) {
            $token    = $iterator->current();
            $delete[] = $token;
            if ($token === $to) {
                break;
            }
            $iterator->next();
        }
        $this->removeTokens($delete);
    }

    /**
     * @return TokenContainerIterator
     */
    public function getIterator()
    {
        return new TokenContainerIterator($this);
    }

    /**
     * @return ReverseTokenContainerIterator
     */
    public function getReverseIterator()
    {
        return new ReverseTokenContainerIterator($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param Token[] $tokens
     *
     * @throws TokenContainerException
     */
    private function setTokens($tokens)
    {
        $this->tokens = [];
        foreach ($tokens as $token) {
            if (!$token instanceof Token) {
                $type    = is_object($token) ? get_class($token) : gettype($token);
                $message = sprintf('Expected instance of token, got "%s"', $type);
                throw new TokenContainerException($message, TokenContainerException::CONTAINER_ONLY_SUPPORTS_TOKENS);
            }
            $this->tokens[] = $token;
        }
    }

    /**
     * @param mixed $offset
     */
    private function checkOffsetType($offset)
    {
        if (null !== $offset && !is_int($offset)) {
            $message = 'TokenContainer only allows integers as offset';
            throw new TokenContainerException($message, TokenContainerException::EXPECTED_OFFSET_TO_BE_INT);
        }
    }

    /**
     * @param Token $token
     */
    private function checkValueType($token)
    {
        if (!$token instanceof Token) {
            $message = 'TokenContainer only allows adding PHP\Manipulator\Token';
            throw new TokenContainerException($message, TokenContainerException::EXPECTED_TOKEN_TO_BE_OF_TYPE_TOKEN);
        }
    }

    /**
     * @param int $offset
     *
     * @return int
     */
    private function getPositionForOffset($offset)
    {
        $this->checkOffsetType($offset);
        $position = 0;

        $this->ensureOffsetExists($offset);

        foreach ($this->tokens as $off => $element) {
            if ($offset === $off) {
                break;
            }
            $position++;
        }

        return $position;
    }

    /**
     * @param $offset
     *
     * @throws TokenContainerException
     */
    private function ensureOffsetExists($offset)
    {
        if (!isset($this->tokens[$offset])) {
            $message = sprintf('Offset "%s" does not exist', $offset);
            throw new TokenContainerException($message, TokenContainerException::OFFSET_DOES_NOT_EXIST);
        }
    }

    /**
     * Insert at a position
     *
     * @param int   $position
     * @param Token $value
     *
     * @return TokenContainer
     */
    private function insertAtPosition($position, Token $value)
    {
        $newContainer = array_slice($this->tokens, 0, $position, true);

        $newContainer[] = $value;

        $after = array_slice($this->tokens, $position);
        foreach ($after as $value) {
            $newContainer[] = $value;
        }

        $this->tokens = $newContainer;

        return $this;
    }

    /**
     * @param Token $token
     */
    private function ensureContainerContainsToken(Token $token)
    {
        if (!$this->contains($token)) {
            $message = sprintf('Container does not contain Token: %s', $token);
            throw new TokenContainerException($message, TokenContainerException::TOKEN_DOES_NOT_EXIST_IN_CONTAINER);
        }
    }
}
