<?php

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
            $message = 'TokenContainer only allows adding PHP_Formatter_Token';
            throw new PHP_Formatter_Exception($message);
        }
    }

    /**
     * Offset Set
     *
     * Implements SPL::ArrayAccess
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
     * Implements SPL::ArrayAccess
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
     * Implements SPL::ArrayAccess
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
     * Implements SPL::ArrayAccess
     *
     * @param integer $offset
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function offsetGet($offset)
    {
        $this->_checkOffsetType($offset);
        if (!isset($this->_container[$offset])) {
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
     * Insert at an offset
     *
     * @param integer $offset
     * @param PHP_Formatter_Token $value
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function insertAtOffset($offset, $value)
    {
        $position = $this->_getPositionForOffset($offset);
        $this->_insertAtPosition($position, $value);
        return $this;
    }

    /**
     * Insert at a position
     *
     * @param integer $position
     * @param PHP_Formatter_Token $value
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    protected function _insertAtPosition($position, $value)
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
     * @param PHP_Formatter_Token $token
     * @return PHP_Formatter_Token|null
     * @todo implementation is ugly and may break because keys are not forced to be without holes!
     */
    public function getPreviousToken(PHP_Formatter_Token $token)
    {
        foreach ($this->_container as $key => $element) {
            if ($element === $token) {
                return isset($this[$key-1]) ? $this[$key-1] : null;
            }
        }
        return null;
    }

    /**
     * @param PHP_Formatter_Token $token
     * @return PHP_Formatter_Token|null
     * @todo implementation is ugly and may break because keys are not forced to be without holes!
     */
    public function getNextToken(PHP_Formatter_Token $token)
    {
        foreach ($this->_container as $key => $element) {
            if ($element === $token) {
                return isset($this[$key+1]) ? $this[$key+1] : null;
            }
        }
        return null;
    }

    /**
     * Get position for offset
     *
     * @param integer $offset
     * @return integer
     */
    protected function _getPositionForOffset($offset)
    {
        $this->_checkOffsetType($offset);
        $position = 0;

        if (!isset($this->_container[$offset])) {
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
            $message = "Container does not contain Token: $after";
            throw new PHP_Formatter_Exception($message);
        }
        $offset = $this->getOffsetByToken($after);
        $position = $this->_getPositionForOffset($offset);
        $this->_insertAtPosition($position + 1, $newToken);
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
     * Creates code from tokens and runs the tokenzier again on them
     *
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function retokenize()
    {
        $code = $this->toString();
        $this->_container = PHP_Formatter_TokenContainer::createTokenArrayFromCode($code);
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
     * @todo rename to toArray ?
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
     * @todo rename to setArray ?
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
     * Implements SPL::IteratorAggregate
     *
     * @return PHP_Formatter_TokenContainer_Iterator
     */
    public function getIterator()
    {
        return new PHP_Formatter_TokenContainer_Iterator($this);
    }

    /**
     * Get a reverse Iterator for traversing the Container from End to begin
     * 
     * @return PHP_Formatter_TokenContainer_ReverseIterator
     */
    public function getReverseIterator()
    {
        return new PHP_Formatter_TokenContainer_ReverseIterator($this);
    }

    /**
     * Creates an array of tokens from code
     *
     * @param string $code
     * @return array
     */
    public static function createTokenArrayFromCode($code)
    {
        $tokenArray = array();
        $tokens = token_get_all($code);
        foreach ($tokens as $token) {
            /* @var $token array|string */
            $tokenArray[] = PHP_Formatter_Token::factory($token);
        }
        return $tokenArray;
    }

    /**
     * Creates a TokenContainer from code
     *
     * @param string $code
     * @return PHP_Formatter_TokenContainer
     */
    public static function createFromCode($code)
    {
        $tokens = PHP_Formatter_TokenContainer::createTokenArrayFromCode($code);
        return new PHP_Formatter_TokenContainer($tokens);
    }
}