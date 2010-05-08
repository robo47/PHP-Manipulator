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
     * Evaluate
     *
     * @param PHP\Manipulator\Token $other
     * @return boolean
     */
    public function evaluate($other)
    {
        if (!$this->_isCountable($other)) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'countable type'
            );
        }
        $expectedCount = $this->_expectedCount;

        if ($expectedCount === $this->_getCount($other)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if the object is countable
     *
     * @param mixed $other
     * @return boolean
     */
    protected function _isCountable($other)
    {
        if ($other instanceof \Countable ||
            $other instanceof \Iterator ||
            is_array($other)) {
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
        if ($other instanceof \Countable ||
            is_array($other)) {
            return count($other);
        }
        if ($other instanceof \Iterator) {
            return \iterator_count($other);
        }
        throw new \Exception('This should not happen! :)');
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
        ' does not match expected count of ' . $this->_expectedCount;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Count matches ';
    }
}