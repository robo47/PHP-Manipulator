<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

class TokenContainerIterator implements \Iterator, \Countable, \SeekableIterator
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
     * @param PHP\Manipulator\TokenContainer $container
     */
    public function __construct(TokenContainer $container)
    {
        $this->_container = $container;
        $this->_keys = array_keys($container->getContainer());
    }

    /**
     *
     * @param integer $position
     * @return integer|false
     */
    protected function _getContainerKeyForPosition($position)
    {
        if (array_key_exists($position, $this->_keys)) {
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
     *
     * @return PHP\Manipulator\Token
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
     *
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

    /**
     *
     */
    public function next()
    {
        $this->_pos++;
    }

    /**
     *
     */
    public function previous()
    {
        $this->_pos--;
    }

    /**
     *
     */
    public function rewind()
    {
        $this->_pos = 0;
    }

    /**
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->_isValidPosition($this->_pos + 1);
    }

    /**
     *
     * @param integer $position
     * @return boolean
     */
    protected function _isValidPosition($position)
    {
        if (array_key_exists($this->_pos, $this->_keys)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param integer $key
     * @return integer|false
     */
    protected function _getPositionForKey($key)
    {
        return array_search($key, $this->_keys);
    }

    /**
     *
     * @param integer $position
     */
    public function seek($key)
    {
        $position = $this->_getPositionForKey($key);
        if (false !== $position) {
            $this->_pos = $position;
        } else {
            throw new \OutOfBoundsException('Position not found');
        }
    }
}