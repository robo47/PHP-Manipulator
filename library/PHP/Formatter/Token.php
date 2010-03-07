<?php

class PHP_Formatter_Token
{

    /**
     * Value
     *
     * @var string
     */
    protected $_value = null;
    /**
     * Linenumber
     *
     * @var integer|null
     */
    protected $_linenumber = null;
    /**
     * Type
     *
     * @var integer|null
     */
    protected $_type = null;

    /**
     *
     * @param string $value
     * @param integer $type
     * @param integer $linenumber
     */
    public function __construct($value, $type = null, $linenumber = null)
    {
        $this->setValue($value);
        $this->setType($type);
        $this->setLinenumber($linenumber);
    }

    /**
     * Factory for token from token_get_all
     *
     * @throws PHP_Formatter_Token
     * @param string|array $input
     * @return PHP_Formatter_Token
     */
    public static function factory($input)
    {
        if (is_array($input)) {
            if (!isset($input[0]) || !isset($input[1])) {
                require_once 'PHP/Formatter/Exception.php';
                $message = 'Array for creating token misses key 0 and/or 1';
                throw new PHP_Formatter_Exception($message);
            }
            if (!isset($input[2])) {
                $token = new PHP_Formatter_Token($input[1], $input[0]);
            } else {
                $token = new PHP_Formatter_Token($input[1], $input[0], $input[2]);
            }
        } elseif (is_string($input)) {
            $token = new PHP_Formatter_Token($input);
        } else {
            require_once 'PHP/Formatter/Exception.php';
            $message = 'invalid datatype for creating a token: ' . gettype($input);
            throw new PHP_Formatter_Exception($message);
        }
        return $token;
    }

    /**
     * Get Value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Set Value
     *
     * @param string $_value
     * @return PHP_Formatter_Token *Provides Fluent Interface*
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * Get Linenumber
     *
     * @return integer|null
     */
    public function getLinenumber()
    {
        return $this->_linenumber;
    }

    /**
     * Set Linenumber
     *
     * @param integer $linenumber
     * @return PHP_Formatter_Token *Provides Fluent Interface*
     */
    public function setLinenumber($linenumber)
    {
        $this->_linenumber = $linenumber;
        return $this;
    }

    /**
     * Get Type
     *
     * @return integer|null
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Set Type
     *
     * @param integer $type
     * @return PHP_Formatter_Token *Provides Fluent Interface*
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * Is Type
     *
     * @param integer|null $type
     * @return boolean
     */
    public function isType($type)
    {
        if ($this->_type === $type) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Equals
     *
     * @param PHP_Formatter_Token $token
     * @return boolean
     */
    public function equals(PHP_Formatter_Token $token, $strict = false)
    {
        $match = false;
        if ($this->getType() === $token->getType()
            && $this->getValue() === $token->getValue()) {
            $match = true;
        }
        if (true === $strict &&
            $this->_linenumber !== $token->getLinenumber()) {
            $match = false;
        }
        return $match;
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }
}