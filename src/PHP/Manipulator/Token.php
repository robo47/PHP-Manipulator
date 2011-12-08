<?php

namespace PHP\Manipulator;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class Token
{

    /**
     * @var string
     */
    protected $_value = null;

    /**
     * @var integer|null
     */
    protected $_linenumber = null;

    /**
     * @var integer|null
     */
    protected $_type = null;

    /**
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
     * @throws PHP\Manipulator\Token
     * @param string|array $input
     * @todo move to "TokenFactory" ?
     * @return \PHP\Manipulator\Token
     */
    public static function factory($input)
    {
        if (is_array($input)) {
            if (!array_key_exists(0, $input) || !array_key_exists(1, $input)) {
                $message = 'Array for creating token misses key 0 and/or 1';
                throw new \Exception($message);
            }
            if (!array_key_exists(2, $input)) {
                $token = new Token($input[1], $input[0]);
            } else {
                $token = new Token($input[1], $input[0], $input[2]);
            }
        } else if (is_string($input)) {
            $token = new Token($input);
        } else {
            $message = 'invalid datatype for creating a token: ' . gettype($input);
            throw new \Exception($message);
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
     * @return \PHP\Manipulator\Token *Provides Fluent Interface*
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
     * @return \PHP\Manipulator\Token *Provides Fluent Interface*
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
     * @return \PHP\Manipulator\Token *Provides Fluent Interface*
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * Equals
     *
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function equals(Token $token, $strict = false)
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
     * Returns the tokens name
     *
     * @return string
     */
    public function getTokenName()
    {
        return token_name($this->getType());
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