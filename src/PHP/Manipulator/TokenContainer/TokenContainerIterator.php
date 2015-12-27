<?php

namespace PHP\Manipulator\TokenContainer;

use Countable;
use Iterator;
use PHP\Manipulator\Exception\TokenContainerIteratorException;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @todo rename
 */
class TokenContainerIterator implements Iterator, Countable
{
    /**
     * @var TokenContainer
     */
    private $container;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var int[]
     */
    protected $keys = [];

    /**
     * @param TokenContainer $container
     */
    public function __construct(TokenContainer $container)
    {
        $this->container = $container;
        $this->refreshKeys();
    }

    protected function refreshKeys()
    {
        $this->keys = array_keys($this->container->toArray());
    }

    /**
     * Updates the Iterator from the container and seeks to the given token
     *
     * @todo name only expresses one job the method does ... updateAndSeekTo ? or drop seek-support ?
     *
     * @param Token $token
     *
     * @return TokenContainerIterator
     */
    public function update(Token $token = null)
    {
        $this->refreshKeys();
        $this->position = 0;
        if (null !== $token) {
            $this->seekToToken($token);
        }

        return $this;
    }

    /**
     * @return TokenContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param int $position
     *
     * @return int|false
     */
    private function getContainerKeyForPosition($position)
    {
        if (isset($this->keys[$position])) {
            return $this->keys[$position];
        }
        throw new TokenContainerIteratorException(
            'Position not valid',
            TokenContainerIteratorException::CURRENT_POSITION_IS_INVALID
        );
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->keys);
    }

    /**
     * @return Token
     */
    public function current()
    {
        $key = $this->getContainerKeyForPosition($this->position);

        return $this->container[$key];
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->getContainerKeyForPosition($this->position);
    }

    public function next()
    {
        $this->position++;
    }

    public function previous()
    {
        $this->position--;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->isValidPosition($this->position);
    }

    /**
     * @param Token $token
     *
     * @throws TokenContainerIteratorException
     *
     * @return TokenContainerIterator
     */
    public function seekToToken(Token $token)
    {
        if ($this->valid() && $this->current() === $token) {
            return $this;
        }
        $key = $this->container->getOffsetByToken($token);
        $this->seek($key);

        return $this;
    }

    /**
     * @throws TokenContainerIteratorException
     *
     * @return Token
     */
    public function getNext()
    {
        $next = null;
        $this->next();
        if ($this->valid()) {
            $next = $this->current();
        }
        $this->previous();

        if ($next !== null) {
            return $next;
        }

        throw new TokenContainerIteratorException(
            'There is no next token',
            TokenContainerIteratorException::NO_NEXT_TOKEN
        );
    }

    /**
     * @throws TokenContainerIteratorException
     *
     * @return Token
     */
    public function getPrevious()
    {
        $previous = null;
        $this->previous();
        if ($this->valid()) {
            $previous = $this->current();
        }
        $this->next();
        if ($previous !== null) {
            return $previous;
        }
        throw new TokenContainerIteratorException(
            'There is no previous token',
            TokenContainerIteratorException::NO_PREVIOUS_TOKEN
        );
    }

    /**
     * @param int $key
     *
     * @return TokenContainerIterator
     */
    private function seek($key)
    {
        $position = $this->getPositionForKey($key);
        if (false === $position) {
            throw new TokenContainerIteratorException(
                'Position not valid',
                TokenContainerIteratorException::CURRENT_POSITION_IS_INVALID
            );
        }
        $this->position = $position;

        return $this;
    }

    /**
     * @param int $position
     *
     * @return bool
     */
    private function isValidPosition($position)
    {
        return array_key_exists($position, $this->keys);
    }

    /**
     * @param int $key
     *
     * @return int|false
     */
    private function getPositionForKey($key)
    {
        return array_search($key, $this->keys, true);
    }
}
