<?php

namespace PHP\Manipulator;

use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer\Iterator;
use PHP\Manipulator\TokenContainer\ReverseIterator;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class TokenContainer
implements \ArrayAccess, \Countable, \IteratorAggregate
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
    public function __construct($input = null)
    {
        if (null !== $input) {
            $this->_init($input);
        }
    }

    /**
     * Init Container from String or array
     *
     * @param array|string $input
     */
    protected function _init($input)
    {
        if (is_string($input)) {
            $this->_container = $this->_createTokensFromCode($input);
        } else {
            foreach ($input as $token) {
                $this[] = $token;
            }
        }
    }

    /**
     * Checks if offset is an integer
     *
     * @throws Exception
     * @param mixed $offset
     */
    protected function _checkOffsetType($offset)
    {
        if (null !== $offset && !is_int($offset)) {
            $message = 'TokenContainer only allows integers as offset';
            throw new \Exception($message);
        }
    }

    /**
     * Checks if Value is PHP\Manipulator\Token
     *
     * @throws Exception
     * @param mixed $value
     */
    protected function _checkValueType($value)
    {
        if (!$value instanceof Token) {
            $message = 'TokenContainer only allows adding PHP\Manipulator\Token';
            throw new \Exception($message);
        }
    }

    /**
     * Offset Set
     *
     * Implements SPL::ArrayAccess
     *
     * @param integer $offset
     * @param \PHP\Manipulator\Token $value
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
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function offsetGet($offset)
    {
        $this->_checkOffsetType($offset);
        if (!isset($this->_container[$offset])) {
            $message = "Offset '$offset' does not exist";
            throw new \Exception($message);
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
     * @param \PHP\Manipulator\Token $value
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function insertAtOffset($offset, Token $value)
    {
        $position = $this->_getPositionForOffset($offset);
        $this->_insertAtPosition($position, $value);

        return $this;
    }

    /**
     * Insert at a position
     *
     * @param integer $position
     * @param \PHP\Manipulator\Token $value
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    protected function _insertAtPosition($position, Token $value)
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
     * @param \PHP\Manipulator\Token $token
     * @return \PHP\Manipulator\Token|null
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
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * @param \PHP\Manipulator\Token $token
     * @return \PHP\Manipulator\Token|null
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
        } catch(\Exception $e) {
            return null;
        }
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
            throw new \Exception($message);
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
     * @throws Exception
     * @param \PHP\Manipulator\Token $token
     * @return integer
     */
    public function getOffsetByToken(Token $token)
    {
        foreach ($this->_container as $offset => $element) {
            if ($element === $token) {
                return $offset;
            }
        }
        $message = "Token '$token' does not exist in this container";
        throw new \Exception($message);
    }

    /**
     * Contains
     *
     * @param \PHP\Manipulator\Token $token
     * @return boolean
     */
    public function contains(Token $token)
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
     * @param \PHP\Manipulator\Token $after
     * @param \PHP\Manipulator\Token $newToken
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function insertTokenAfter(Token $after, Token $newToken)
    {
        if (!$this->contains($after)) {
            $message = "Container does not contain Token: $after";
            throw new \Exception($message);
        }
        $offset = $this->getOffsetByToken($after);
        $position = $this->_getPositionForOffset($offset);
        $this->_insertAtPosition($position + 1, $newToken);

        return $this;
    }

    /**
     * Insert Token Before
     *
     * @param \PHP\Manipulator\Token $after
     * @param \PHP\Manipulator\Token $newToken
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function insertTokenBefore(Token $before, Token $newToken)
    {
        if (!$this->contains($before)) {
            $message = "Container does not contain Token: $before";
            throw new \Exception($message);
        }
        $iterator = $this->getIterator();
        $iterator->seekToToken($before);
        $iterator->previous();
        $position = -1;
        if ($iterator->valid()) {
            $position = $this->_getPositionForOffset($iterator->key());
        }
        $this->_insertAtPosition($position+1, $newToken);

        return $this;
    }

    /**
     * Insert Tokens After
     *
     * @param \PHP\Manipulator\Token $after
     * @param array $newTokens
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function insertTokensBefore(Token $before, array $newTokens)
    {
        if (!$this->contains($before)) {
            $message = "Container does not contain Token: $before";
            throw new \Exception($message);
        }
        $newTokens = array_reverse($newTokens);
        foreach ($newTokens as $newToken) {
            $this->insertTokenBefore($before, $newToken);
            $before = $newToken;
        }

        return $this;
    }

    /**
     * Insert Tokens After
     *
     * @param \PHP\Manipulator\Token $after
     * @param array $newTokens
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function insertTokensAfter(Token $after, array $newTokens)
    {
        if (!$this->contains($after)) {
            $message = "Container does not contain Token: $after";
            throw new \Exception($message);
        }
        foreach ($newTokens as $newToken) {
            $this->insertTokenAfter($after, $newToken);
            $after = $newToken;
        }

        return $this;
    }

    /**
     * @param string $code
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     * @todo name is ugly
     */
    public function updateFromCode($code)
    {
        $this->_container = $this->_createTokensFromCode($code);

        return $this;
    }

    /**
     * Creates code from tokens and runs the tokenzier again on them
     *
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function retokenize()
    {
        $this->updateFromCode($this->toString());

        return $this;
    }

    /**
     * Remove Tokens
     *
     * @param array $tokens
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
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
     * @param \PHP\Manipulator\Token $token
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function removeToken(Token $token)
    {
        $offset = $this->getOffsetByToken($token);
        unset($this[$offset]);

        return $this;
    }

    /**
     * Creates Code from the tokens
     *
     * @param \PHP\Manipulator\TokenContainer $tokens
     * @return string
     */
    public function toString()
    {
        $code = '';
        foreach ($this as $token) {
            /* @var $token PHP\Manipulator\Token */
            $code .= (string) $token;
        }

        return $code;
    }

    /**
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
    public function toArray()
    {
        return $this->_container;
    }

    /**
     * Set Container
     *
     * @param array $container
     * @return \PHP\Manipulator\TokenContainer *Provides Fluent Interface*
     */
    public function setArray(array $container)
    {
        $this->_container = $container;

        return $this;
    }

    /**
     * Removes a sequence of Tokens from a token to another (including start and end-token)
     *
     * @param \PHP\Manipulator\Token $from
     * @param \PHP\Manipulator\Token $end
     */
    public function removeTokensFromTo(Token $from, Token $to)
    {
        $iterator = $this->getIterator();
        $iterator->seekToToken($from);

        $delete = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            $delete[] = $token;
            if ($token === $to) {
                break;
            }
            $iterator->next();
        }
        $this->removeTokens($delete);
    }

    /**
     * Get Iterator for traversing the Container from begin to end
     *
     * Implements SPL::IteratorAggregate
     *
     * @return \PHP\Manipulator\TokenContainer\Iterator
     */
    public function getIterator()
    {
        return new Iterator($this);
    }

    /**
     * Get a reverse Iterator for traversing the Container from end to begin
     *
     * @return \PHP\Manipulator\TokenContainer\ReverseIterator
     */
    public function getReverseIterator()
    {
        return new ReverseIterator($this);
    }

    /**
     * @param string $code
     * @return array
     */
    protected function _createTokensFromCode($code)
    {
        $array = array();
        $tokens = token_get_all($code);
        foreach ($tokens as $token) {
            /* @var $token array|string */
            $array[] = Token::factory($token);
        }

        return $array;
    }
}
