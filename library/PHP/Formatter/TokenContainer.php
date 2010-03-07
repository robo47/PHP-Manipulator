<?php

require_once 'PHP/Formatter/Token.php';

class PHP_Formatter_TokenContainer
implements ArrayAccess, Countable, IteratorAggregate
{

    /**
     * Container with Tokens
     *
     * @var array
     */
    protected $_container = array();

    /**
     * Constructor
     * 
     * @param array $tokens
     */
    public function __construct(array $tokens = array())
    {
        foreach ($tokens as $token) {
            $this[] = $token;
        }
    }

    /**
     * Checks if offset is an integer
     *
     * @throws PHP_Formatter_Exception
     * @param mixed $offset
     */
    protected function _checkOffsetType($offset)
    {
        if (null !== $offset && !is_int($offset)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = 'TokenContainer only allows integers as offset';
            throw new PHP_Formatter_Exception($message);
        }
    }

    /**
     * Checks if Value is PHP_Formatter_Token
     *
     * @throws PHP_Formatter_Exception
     * @param mixed $value
     */
    protected function _checkValueType($value)
    {
        if (!$value instanceof PHP_Formatter_Token) {
            require_once 'PHP/Formatter/Exception.php';
            $message = 'TokenContainer only allows adding PHP_Formatter_Token';
            throw new PHP_Formatter_Exception($message);
        }
    }

    /**
     * Offset Set
     *
     * @param integer $offset
     * @param PHP_Formatter_Token $value
     */
    public function offsetSet($offset, $value)
    {
        $this->_checkOffsetType($offset);
        $this->_checkValueType($value);

        if (null === $offset) {
            $this->_container[] = $value;
        } else {
            $this->_container[$offset] = $value;
        }
    }

    /**
     * Offset exists
     *
     * @param integer $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        $this->_checkOffsetType($offset);
        return isset($this->_container[$offset]);
    }

    /**
     * Offset unset
     *
     * @param integer $offset
     */
    public function offsetUnset($offset)
    {
        $this->_checkOffsetType($offset);
        unset($this->_container[$offset]);
    }

    /**
     * Offset Get
     *
     * @param integer $offset
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function offsetGet($offset)
    {
        $this->_checkOffsetType($offset);
        if (!isset($this->_container[$offset])) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Offset '$offset' does not exist";
            throw new PHP_Formatter_Exception($message);
        }
        return $this->_container[$offset];
    }

    /**
     * Count
     *
     * Implements SPL::Countable
     *
     * @return integer
     */
    public function count()
    {
        return count($this->_container);
    }

    /**
     * Insert at offset
     *
     * @param integer $position
     * @param PHP_Formatter_Token $value
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function insertAtPosition($position, $value)
    {
        $this->_checkValueType($value);

        $newContainer = array_slice($this->_container, 0, $position, true);

        $newContainer[] = $value;

        $after = array_slice($this->_container, $position);
        foreach ($after as $value) {
            $newContainer[] = $value;
        }

        $this->_container = $newContainer;
        return $this;
    }

    /**
     * Get position for offset
     *
     * @param integer $offset
     * @return integer
     */
    public function getPositionForOffset($offset)
    {
        $this->_checkOffsetType($offset);
        $position = 0;

        if (!isset($this->_container[$offset])) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Offset '$offset' does not exist";
            throw new PHP_Formatter_Exception($message);
        }

        foreach ($this->_container as $off => $element) {
            if ($offset === $off) {
                break;
            }
            $position++;
        }
        return $position;
    }

    /**
     * Get Offset By Token
     *
     * Returns the offset of a token if it exists in the Container
     *
     * @throws PHP_Formatter_Exception
     * @param PHP_Formatter_Token $token
     * @return integer
     */
    public function getOffsetByToken(PHP_Formatter_Token $token)
    {
        $tokenOffset = null;
        foreach ($this->_container as $offset => $element) {
            if ($element === $token) {
                $tokenOffset = $offset;
            }
        }
        if (null === $tokenOffset) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Token '$token' does not exist in this container";
            throw new PHP_Formatter_Exception($message);
        }
        return $tokenOffset;
    }

    /**
     * Contains
     *
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    public function contains(PHP_Formatter_Token $token)
    {
        $contains = false;
        foreach ($this->_container as $element) {
            if ($element === $token) {
                $contains = true;
                break;
            }
        }
        return $contains;
    }

    /**
     * Insert Token After
     *
     * @param PHP_Formatter_Token $after
     * @param PHP_Formatter_Token $newToken
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function insertTokenAfter(PHP_Formatter_Token $after, PHP_Formatter_Token $newToken)
    {
        if (!$this->contains($after)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Container does not contain Token: $after";
            throw new PHP_Formatter_Exception($message);
        }
        $offset = $this->getOffsetByToken($after);
        $position = $this->getPositionForOffset($offset);
        $this->insertAtPosition($position + 1, $newToken);
        return $this;
    }

    /**
     * Insert Tokens After
     *
     * @param PHP_Formatter_Token $after
     * @param array $newTokens
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function insertTokensAfter(PHP_Formatter_Token $after, array $newTokens)
    {
        if (!$this->contains($after)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Container does not contain Token: $after";
            throw new PHP_Formatter_Exception($message);
        }
        foreach ($newTokens as $newToken) {
            $this->insertTokenAfter($after, $newToken);
            $after = $newToken;
        }
        return $this;
    }

    /**
     * Remove Tokens
     *
     * @param array $tokens
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function removeTokens(array $tokens)
    {
        foreach ($tokens as $token) {
            $this->removeToken($token);
        }
        return $this;
    }

    /**
     * Remove Token
     *
     * @param PHP_Formatter_Token $token
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function removeToken(PHP_Formatter_Token $token)
    {
        $offset = $this->getOffsetByToken($token);
        unset($this[$offset]);
        return $this;
    }

    /**
     * Creates Code from a TokenArray
     *
     * @param PHP_Formatter_TokenContainer $tokens
     * @return string
     */
    public function toString()
    {
        $code = '';
        foreach ($this as $token) {
            /* @var $token PHP_Formatter_Token */
            $code .= (string) $token;
        }
        return $code;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Get Container
     *
     * @return array
     */
    public function getContainer()
    {
        return $this->_container;
    }

    /**
     * Set Container
     *
     * @todo strict checking ?
     * @param array $container
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function setContainer(array $container)
    {
        $this->_container = $container;
        return $this;
    }

    /**
     * Get Iterator
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        // @todo extra iterator only having iteration-stuff
        return new ArrayIterator($this->_container);
    }

    /**
     * Creates a TokenArray from code
     *
     * @param string $code
     * @return PHP_Formatter_TokenContainer
     */
    public static function createFromCode($code)
    {
        $container = new PHP_Formatter_TokenContainer();
        $tokens = token_get_all($code);
        foreach ($tokens as $token) {
            /* @var $token array|string */
            $container[] = PHP_Formatter_Token::factory($token);
        }
        return $container;
    }
}