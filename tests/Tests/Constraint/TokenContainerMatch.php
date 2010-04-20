<?php

namespace Tests\Constraint;

use \PHP\Manipulator\TokenContainer;
use \PHP\Manipulator\Token;
use \Tests\Util;

class TokenContainerMatch extends \PHPUnit_Framework_Constraint
{

    /**
     * @var PHP\Manipulator\TokenContainer
     */
    protected $_expectedContainer = null;

    /**
     * @var boolean
     */
    protected $_strict = false;

    /**
     *
     * @param PHP\Manipulator\TokenContainer $expected
     * @param boolean $strict
     */
    public function __construct($expected, $strict)
    {
        if (!$expected instanceof TokenContainer) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'PHP\Manipulator\TokenContainer'
            );
        }

        if (!is_bool($strict)) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                2, 'boolean'
            );
        }

        $this->_expectedContainer = $expected;
        $this->_strict = $strict;
    }

    /**
     *
     * @param PHP\Manipulator\TokenContainer $other
     * @return boolean
     */
    public function evaluate($other)
    {
        if (!$other instanceof TokenContainer) {
            throw \PHPUnit_Util_InvalidArgumentHelper::factory(
                1, 'PHP\Manipulator\TokenContainer'
            );
        }

        $expectedIterator = $this->_expectedContainer->getIterator();
        $actualIterator = $other->getIterator();

        $i = 0;
        while ($expectedIterator->valid() && $actualIterator->valid()) {

            $expectedToken = $expectedIterator->current();
            /* @var $expectedToken PHP\Manipulator\Token */

            $actualToken = $actualIterator->current();
            /* @var $actualToken PHP\Manipulator\Token */

            if (!$actualToken->equals($expectedToken, $this->_strict)) {
                return false;
            }

            $i++;
            $expectedIterator->next();
            $actualIterator->next();
        }

        if ($expectedIterator->valid() || $actualIterator->valid()) {
            return false;
        }
        return true;
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     */
    protected function failureDescription($other, $description, $not)
    {
        $containerDiff = Util::compareContainers(
                $this->_expectedContainer,
                $other,
                $this->_strict
        );

        $message = 'Tokens are different: [length]' . PHP_EOL .
            PHP_EOL . $containerDiff;

        return $message;
    }

    /**
     * Compare Containers
     *
     * Returns string-presentation of both containers next to each other
     *
     * @param \PHP\Manipulator\TokenContainer $first
     * @param \PHP\Manipulator\TokenContainer $secod
     */
    public function compareContainers(TokenContainer $first,
        TokenContainer $second)
    {
        $firstDump = Util::dumpContainer($first);
        $secondDump = Util::dumpContainer($second);

        $firstDumpAsArray = preg_split('~(\n|\r\n|\r)~', $firstDump);
        $secondDumpAsArray = preg_split('~(\n|\r\n|\r)~', $secondDump);

        $cOneIterator = new \ArrayIterator($firstDumpAsArray);
        $cTwoIterator = new \ArrayIterator($secondDumpAsArray);

        $length = Util::getLongestLineLength($firstDumpAsArray);

        $cOneCount = (count($cOneIterator) - 2);
        $cTwoCount = (count($cTwoIterator) - 2);

        $code = '';
        $code .= str_pad('Tokens: ' . $cOneCount, ($length + 6), ' ', STR_PAD_BOTH) . ' |';
        $code .= str_pad('Tokens: ' . $cTwoCount, ($length + 6), ' ', STR_PAD_BOTH);
        $code .= PHP_EOL;

        $i = 1;
        while ($cOneIterator->valid() || $cTwoIterator->valid()) {

            $line1 = '';
            $line2 = '';

            if ($cOneIterator->valid()) {
                $line1 = (string) $cOneIterator->current();
                $cOneIterator->next();
            }

            if ($cTwoIterator->valid()) {
                $line2 = (string) $cTwoIterator->current();
                $cTwoIterator->next();
            }

            // is STRICT! ignores not set linenumber
            if ($line1 != $line2) {
                $code .= '####### NEXT IS DIFFERENT ## ' . PHP_EOL;
            }
            $currLine = '';
            $currLine .= str_pad($line1, ($length + 1), ' ', STR_PAD_RIGHT) . ' |  ';
            $currLine .= $line2 . PHP_EOL;

            $j = '';
            if ($i > 2) {
                $j = ($i - 2) . ')';
            }
            $code .= str_pad($j, 4, ' ', STR_PAD_LEFT) . ' ' . $currLine;
            $i++;
        }
        return $code;
    }

    /**
     *
     * @return string
     */
    public function toString()
    {
        return 'TokenContainer matches another Container';
    }
}