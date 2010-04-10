<?php

namespace Tests\Constraint;

class Count extends \PHPUnit_Framework_Constraint
{

    /**
     * @var PHP\Manipulator\Token
     */
    protected $_expectedCount = null;

    /**
     *
     * @param integer $expected
     * @param boolean $strict
     */
    public function __construct($expected)
    {
        if (!is_int($expected)) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'integer'
            );
        }

        $this->_expectedCount = $expected;
    }

    /**
     *
     * @param PHP\Manipulator\Token $other
     * @return boolean
     */
    public function evaluate($other)
    {
        if (!$this->_isCountable($other)) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'not countable'
            );
        }
        $expectedCount = $this->_expectedCount;

        if ($expectedCount === $this->_getCount($other)) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param mixed $other
     * @return boolean
     */
    public function _isCountable($other)
    {
        if ($other instanceof \Countable) {
            return true;
        }
        if ($other instanceof \Iterator) {
            return true;
        }
        if (is_array($other)) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param mixed $other
     * @return boolean
     */
    protected function _getCount($other)
    {
        if ($other instanceof \Countable) {
            return count($other);
        }
        if ($other instanceof \Iterator) {
            return \iterator_count($other);
        }
        if (is_array($other)) {
            return count($other);
        }
        throw new \Exception('unexpected event: hell froze over!');
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     * @return string
     */
    protected function failureDescription($other, $description, $not)
    {
        return 'Count of ' . $this->_getCount($other) .
            ' does not match exptected count of ' . $this->_expectedCount;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Count matches ';
    }
}