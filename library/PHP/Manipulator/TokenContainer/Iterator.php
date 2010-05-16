<?php

namespace PHP\Manipulator\TokenContainer;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class Iterator implements \Iterator, \Countable, \SeekableIterator
{

    /**
     * @var PHP\Manipulator\TokenContainer
     */
    protected $_container = null;

    /**
     * Current Position in the $this->_keys-array
     *
     * @var integer
     */
    protected $_pos = 0;

    /**
     * @var array
     */
    protected $_keys = array();

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function __construct(TokenContainer $container)
    {
        $this->_container = $container;
        $this->_init();
    }

    protected function _init()
    {
        $this->_keys = array_keys($this->_container->toArray());
    }

    /**
     * Reinits the Iterator from the container and resets it's position;
     *
     * @param \PHP\Manipulator\Token $token
     * @return \PHP\Manipulator\TokenContainer\Iterator *Provides Fluent Interface*
     */
    public function update(Token $token = null)
    {
        $this->_init();
        $this->_pos = 0;
        if (null !== $token) {
            $this->seekToToken($token);
        }
        return $this;
    }

    /**
     * @return \PHP\Manipulator\TokenContainer
     */
    public function getContainer()
    {
        return $this->_container;
    }

    /**
     * @param integer $position
     * @return integer|false
     */
    protected function _getContainerKeyForPosition($position)
    {
        if (isset($this->_keys[$position])) {
            return $this->_keys[$position];
        } else {
            return false;
        }
    }

    /**
     * Implements SPL::Countable
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_keys);
    }

    /**
     * @return \PHP\Manipulator\Token
     */
    public function current()
    {
        $key = $this->_getContainerKeyForPosition($this->_pos);
        if (false === $key) {
            throw new \OutOfBoundsException('Position not valid');
        }
        return $this->_container[$key];
    }

    /**
     * @return integer
     */
    public function key()
    {
        $key = $this->_getContainerKeyForPosition($this->_pos);
        if (false === $key) {
            throw new \OutOfBoundsException('Position not valid');
        }
        return $key;
    }

    public function next()
    {
        $this->_pos++;
    }

    public function previous()
    {
        $this->_pos--;
    }

    public function rewind()
    {
        $this->_pos = 0;
    }

    /**
     * @return boolean
     */
    public function valid()
    {
        return $this->_isValidPosition($this->_pos);
    }

    /**
     * @param integer $position
     * @return boolean
     */
    protected function _isValidPosition($position)
    {
        if (isset($this->_keys[$position])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param integer $key
     * @return integer|false
     */
    protected function _getPositionForKey($key)
    {
        return array_search($key, $this->_keys);
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return \PHP\Manipulator\TokenContainer\Iterator *Provides Fluent Interface*
     */
    public function seekToToken(Token $token)
    {
        if ($this->valid() && $this->current() === $token) {
            return $this;
        }
        $key = $this->_container->getOffsetByToken($token);
        $this->seek($key);
        return $this;
    }

    /**
     * @param integer $position
     * @return \PHP\Manipulator\TokenContainer\Iterator *Provides Fluent Interface*
     */
    public function seek($key)
    {
        $position = $this->_getPositionForKey($key);
        if (false !== $position) {
            $this->_pos = $position;
        } else {
            throw new \OutOfBoundsException('Position not found');
        }
        return $this;
    }

    /**
     * Returns null of no next token exists
     *
     * @return \PHP\Manipulator\Token
     */
    public function getNext()
    {
        $next = null;
        $this->next();
        if ($this->valid()) {
            $next = $this->current();
        }
        $this->previous();
        return $next;
    }

    /**
     * Returns null of no previous token exists
     * @return \PHP\Manipulator\Token
     */
    public function getPrevious()
    {
        $previous = null;
        $this->previous();
        if ($this->valid()) {
            $previous = $this->current();
        }
        $this->next();
        return $previous;
    }
}