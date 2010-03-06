<?php

require_once 'PHP/Formatter/Token.php';

class PHP_Formatter_TokenContainer implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Container with Tokens
     * 
     * @var array
     */
    protected $_container = array();

    /**
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
            $message = 'TokenArray only allows integers as offset';
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
            $message = 'TokenArray only allows adding PHP_Formatter_Token';
            throw new PHP_Formatter_Exception($message);
        }
    }

    /**
     *
     * @param integer $offset
     * @param PHP_Formatter_Token $value
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
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
        return $this;
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
     *
     * @param integer $offset
     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     */
    public function offsetGet($offset)
    {
        $this->_checkOffsetType($offset);
        if(!isset($this->_container[$offset])) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Offset '$offset' does not exist";
            throw new PHP_Formatter_Exception($message);
        }
        return $this->_container[$offset];
    }

    /**
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
     *
     * @param integer $offset
     * @return integer
     */
    public function getPositionForOffset($offset)
    {
        $this->_checkOffsetType($offset);
        $position = 0;
        
        if(!isset($this->_container[$offset])) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Offset '$offset' does not exist";
            throw new PHP_Formatter_Exception($message);
        }

        foreach($this->_container as $off => $element) {
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
        foreach($this->_container as $offset => $element) {
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

//    /**
//     *
//     * @param integer $offset
//     * @param PHP_Formatter_Token  $value
//     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     //*/
//    public function insertAfterOffset($offset, $value)
//    {
//        $this->_checkOffsetType($offset);
//        $this->_checkValueType($value);
//        $this->insertAtOffset($offset + 1, $value);
//        return $this;
//    }
//
//    /**
//     *
//     * @param integer $offset
//     * @param PHP_Formatter_Token  $value
//     * @return PHP_Formatter_TokenContainer *Provides Fluent Interface*
     //*/
//    public function insertBeforeOffset($offset, $value)
//    {
//        $this->_checkOffsetType($offset);
//        $this->_checkValueType($value);
//        $this->insertAtPosition($offset-1, $value);
//        return $this;
//    }

    /**
     * Creates a TokenArray from code
     *
     * @param string $code
     * @return PHP_Formatter_TokenContainer
     */
    public static function createTokenArrayFromCode($code)
    {
        $tokenArray = new PHP_Formatter_TokenContainer();
        $tokens = token_get_all($code);
        foreach ($tokens as $token) {
            /* @var $token array|string */
            $tokenArray[] = PHP_Formatter_Token::factory($token);
        }
        return $tokenArray;
    }

    /**
     *
     * @param string $file
     * @return PHP_Formatter_TokenContainer
     */
    public static function createTokenArrayFromFile($file)
    {
        if (!file_exists($file)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Path '$file' does not exist";
            throw new PHP_Formatter_Exception($message);
        }

        if (!is_file($file)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "Path '$file' is no file";
            throw new PHP_Formatter_Exception($message);
        }

        if (!is_readable($file)) {
            require_once 'PHP/Formatter/Exception.php';
            $message = "File '$file' is not readable";
            throw new PHP_Formatter_Exception($message);
        }

        return self::createTokenArrayFromCode(
                file_get_contents($file)
        );
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
            $code .= (string)$token;
        }
        return $code;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     *
     * @return array
     */
    public function getContainer()
    {
        return $this->_container;
    }

    /**
     *
     * @todo strict checking ?
     * @param array $container
     * @return <type>
     */
    public function setContainer(array $container)
    {
        $this->_container = $container;
        return $this;
    }

    /**
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        // @todo extra iterator only having iteration-stuff
        return new ArrayIterator($this->_container);
    }
}