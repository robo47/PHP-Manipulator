<?php

namespace Tests;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;
use Tests\Constraint\TokensMatch as TokensMatchConstraint;

class Util
{

    /**
     * Dump Container
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @return string
     */
    public static function dumpContainer(TokenContainer $container)
    {
        $dump = '';
        $iterator = $container->getIterator();
        $dump .= str_pad('Token', 28, ' ', STR_PAD_RIGHT) . '| ' .
            str_pad('LEN', 4, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad('LINE', 4, ' ', STR_PAD_LEFT) . ' | VALUE' .
            PHP_EOL . PHP_EOL;

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP\Manipulator\Token */
            $dump .= Util::dumpToken($token);
            $iterator->next();
        }

        return trim($dump);
    }

    /**
     * Compare Containers
     *
     * Returns string-presentation of both containers next to each other
     *
     * @param \PHP\Manipulator\TokenContainer $expected
     * @param \PHP\Manipulator\TokenContainer $secod
     */
    public static function compareContainers(TokenContainer $expectedContainer,
        TokenContainer $actualContainer, $strict)
    {
        $expectedIterator = new \ArrayIterator($expectedContainer->getContainer());
        $actualIterator = new \ArrayIterator($actualContainer->getContainer());

        $values = array();
        $longest = 0;

        while ($actualIterator->valid() || $expectedIterator->valid()) {

            $expected = '';
            $actual = '';
            $missmatch = true;

            if ($expectedIterator->valid()) {
                $expected = (string) self::dumpToken($expectedIterator->current(), false);
            }
            if ($actualIterator->valid()) {
                $actual = (string) self::dumpToken($actualIterator->current(), false);
            }

            if ($actualIterator->valid() && $expectedIterator->valid()) {
                $constraint = new TokensMatchConstraint($expectedIterator->current(), $strict);
                $missmatch = !$constraint->evaluate($actualIterator->current());
            }

            $values[] = array(
                'actual' => $actual,
                'expected' => $expected,
                'missmatch' => $missmatch,
            );

            if (strlen($actual) > $longest) {
                $longest = strlen($actual);
            }

            if (strlen($expected) > $longest) {
                $longest = strlen($expected);
            }

            $expectedIterator->next();
            $actualIterator->next();
        }

        $comparision = '    ';
        $comparision .= str_pad('Tokens: ' . count($expectedContainer), $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= ' | ';
        $comparision .= str_pad('Tokens: ' . count($actualContainer), $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= PHP_EOL;
        $comparision .= PHP_EOL;
        $i = 0;
        foreach ($values as $val) {
            if (true === $val['missmatch']) {
                $comparision .= '####### NEXT IS DIFFERENT ## ' . PHP_EOL;
            }
            $comparision .= str_pad($i . ') ', 4, ' ');
            $comparision .= str_pad($val['expected'], $longest + 2, ' ');
            $comparision .= ' | ';
            $comparision .= str_pad($val['actual'], $longest + 2, ' ');
            $comparision .= PHP_EOL;
            $i++;
        }
        $comparision = rtrim($comparision);
        return $comparision;
    }

    /**
     * Get longest lines length
     *
     * Returns the length of the longest string in the array
     *
     * @param array $array
     * @return integer
     */
    public static function getLongestLineLength(array $array)
    {
        $longest = 0;
        foreach ($array as $line) {
            $length = mb_strlen($line, 'utf-8');
            if ($length > $longest) {
                $longest = $length;
            }
        }
        return $longest;
    }

    /**
     * Dump a token
     *
     * Replaces spaces, linebreaks and tabs with visual representations:
     * \t \r\n \n \r .
     *
     * @param \PHP\Manipulator\Token $token
     * @return string
     */
    public static function dumpToken(Token $token, $add = true)
    {
        $type = $token->getType();
        $value = $token->getValue();
        $typeName = '[SIMPLE]';
        if (null !== $type) {
            $typeName = token_name($token->getType());
        }
        $length = (string) mb_strlen($value, 'utf-8');
        $search = array("\n\r", "\n", "\r", "\t", " ");
        $replace = array("\\n\\r", "\\n", "\\r", "\\t", ".");

        $value = str_replace($search, $replace, $value);

        $line = $token->getLinenumber();

        if (null === $line) {
            $line = 'NULL';
        }
        if ($add) {
            return str_pad($typeName, 28, ' ', STR_PAD_RIGHT) . '| ' .
                str_pad($length, 4, ' ', STR_PAD_LEFT) . ' | ' .
                str_pad($line, 4, ' ', STR_PAD_LEFT) . ' | ' . $value . PHP_EOL;
        } else {
            return str_pad($typeName, 28, ' ', STR_PAD_RIGHT) . '| ' .
                str_pad($length, 4, ' ', STR_PAD_LEFT) . ' | ' .
                str_pad($line, 4, ' ', STR_PAD_LEFT) . ' | ' . $value;
        }
    }



    /**
     *
     * @param \PHP\Manipulator\Tokenfinder\Result $expectedResult
     * @param \PHP\Manipulator\Tokenfinder\Result $actualResult
     */
    public static function compareResults(Result $expectedResult, Result $actualResult)
    {
        $expectedIterator = new \ArrayIterator($expectedResult->getTokens());
        $actualIterator = new \ArrayIterator($actualResult->getTokens());

        $values = array();
        $longest = 0;

        while ($actualIterator->valid() || $expectedIterator->valid()) {

            $expected = '';
            $actual = '';

            if ($expectedIterator->valid()) {
                $expected = (string) self::dumpToken($expectedIterator->current(), false);
            }
            if ($actualIterator->valid()) {
                $actual = (string) self::dumpToken($actualIterator->current(), false);
            }

            $values[] = array(
                'actual' => $actual,
                'expected' => $expected,
                'missmatch' => (bool) ($actualIterator->current() === $expectedIterator)
            );

            if (strlen($actual) > $longest) {
                $longest = strlen($actual);
            }

            if (strlen($expected) > $longest) {
                $longest = strlen($expected);
            }

            $expectedIterator->next();
            $actualIterator->next();
        }

        $comparision = '    ';
        $comparision .= str_pad('expected (' . count($expectedResult) . ')', $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= ' | ';
        $comparision .= str_pad('actual(' . count($actualResult) . ')', $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= PHP_EOL;
        $comparision .= PHP_EOL;
        $i = 0;
        foreach ($values as $val) {
            if (true === $val['missmatch']) {
                $comparision .= '####### NEXT IS DIFFERENT ## ' . PHP_EOL;
            }
            $comparision .= str_pad($i . ') ', 4, ' ');
            $comparision .= str_pad($val['expected'], $longest + 2, ' ');
            $comparision .= ' | ';
            $comparision .= str_pad($val['actual'], $longest + 2, ' ');
            $comparision .= PHP_EOL;
            $i++;
        }
        return $comparision;
    }
}