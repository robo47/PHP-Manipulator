<?php

class PHP_Formatter_TokenContainer_Iterator implements Iterator, Countable, SeekableIterator
{
    /**
     * @var PHP_Formatter_TokenContainer
     */
    protected $_container = null;

    /**
     * Current Position in the $this->_keys-array
     *
     * @var integer
     */
    protected $_pos = 0;

    /**
     *
     * @var array
     */
    protected $_keys = array();

    /**
     *
     * @param PHP_Formatter_TokenContainer $container
     */
    public function __construct(PHP_Formatter_TokenContainer $container)
    {
        $this->_container = $container;
        $this->_keys = array_keys($container->getContainer());
    }

    protected function _getContainerKeyForPosition($position)
    {
        return $this->_keys[$position];
    }

    /**
     *
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
     * @return PHP_Formatter_Token
     */
    public function current()
    {
        if ($this->valid()) {
            return $this->_container[$this->_getContainerKeyForPosition($this->_pos)];
        }
        require_once 'PHP/Formatter/Exception.php';
        $message = 'Can\'t get element if iterator is at invalid position';
        throw new PHP_Formatter_Exception($message);
    }

    /**
     *
     * @return integer
     */
    public function key()
    {
        if ($this->_isValidPosition($this->_pos)) {
            return $this->_getContainerKeyForPosition($this->_pos);
        }
        require_once 'PHP/Formatter/Exception.php';
        $message = 'No key available';
        throw new PHP_Formatter_Exception($message);
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
        return $this->_isValidPosition($this->_pos+1);
    }

    /**
     *
     * @param integer $position
     * @return boolean
     */
    protected function _isValidPosition($position)
    {
        if(array_key_exists($this->_pos, $this->_keys)) {
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
        if(false !== $position) {
            $this->_pos = $position;
        } else {
            throw new OutOfBoundsException();
        }
    }
}
