<?php

namespace Tests;

use ArrayIterator;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenFinder\Result;
use Tests\Constraint\TokensMatch as TokensMatchConstraint;

class Util
{
    /**
     * Dump Container
     *
     * @param TokenContainer $container
     *
     * @return string
     */
    public static function dumpContainer(TokenContainer $container)
    {
        $dump     = '';
        $iterator = $container->getIterator();
        $dump .= str_pad('Token', 28, ' ', STR_PAD_RIGHT).'| '.
            str_pad('LEN', 4, ' ', STR_PAD_LEFT).' | '.
            str_pad('LINE', 4, ' ', STR_PAD_LEFT).' | VALUE'.
            PHP_EOL.PHP_EOL;

        while ($iterator->valid()) {
            $token = $iterator->current();
            $dump .= self::dumpToken($token).PHP_EOL;
            $iterator->next();
        }

        return trim($dump);
    }

    /**
     * Compare Containers.
     *
     * Returns string-presentation of both containers next to each other.
     *
     * @param TokenContainer $expectedContainer
     * @param TokenContainer $actualContainer
     * @param bool           $strict
     *
     * @return string
     */
    public static function compareContainers(
        TokenContainer $expectedContainer,
        TokenContainer $actualContainer,
        $strict
    ) {
        $expectedIterator = new ArrayIterator($expectedContainer->toArray());
        $actualIterator   = new ArrayIterator($actualContainer->toArray());

        $values  = [];
        $longest = 0;

        while ($actualIterator->valid() || $expectedIterator->valid()) {
            $expected  = '';
            $actual    = '';
            $missmatch = true;

            if ($expectedIterator->valid()) {
                $expected = (string) self::dumpToken($expectedIterator->current());
            }
            if ($actualIterator->valid()) {
                $actual = (string) self::dumpToken($actualIterator->current());
            }

            if ($actualIterator->valid() && $expectedIterator->valid()) {
                $constraint = new TokensMatchConstraint($expectedIterator->current(), $strict);
                $missmatch  = !$constraint->evaluate($actualIterator->current(), '', true);
            }

            $values[] = [
                'actual'    => $actual,
                'expected'  => $expected,
                'missmatch' => $missmatch,
            ];

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
        $comparision .= str_pad('Tokens: '.count($expectedContainer), $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= ' | ';
        $comparision .= str_pad('Tokens: '.count($actualContainer), $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= PHP_EOL;
        $comparision .= PHP_EOL;
        $i = 0;
        foreach ($values as $val) {
            if (true === $val['missmatch']) {
                $comparision .= '####### NEXT IS DIFFERENT ## '.PHP_EOL;
            }
            $comparision .= str_pad($i.') ', 4, ' ');
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
     * Dump a token
     *
     * Replaces spaces, linebreaks and tabs with visual representations:
     * \t \r\n \n \r .
     *
     * @param Token $token
     *
     * @return string
     */
    public static function dumpToken(Token $token)
    {
        $type     = $token->getType();
        $value    = $token->getValue();
        $typeName = '[SIMPLE]';
        if (null !== $type) {
            $typeName = $token->getTokenName();
        }
        $length  = (string) mb_strlen($value, 'utf-8');
        $search  = ["\n\r", "\n", "\r", "\t", ' '];
        $replace = ['\\n\\\\r', '\\n', '\\r', '\\t', '.'];

        $value = str_replace($search, $replace, $value);

        $line = $token->getLineNumber();

        if (null === $line) {
            $line = 'NULL';
        }

        return str_pad($typeName, 28, ' ', STR_PAD_RIGHT).'| '.
        str_pad($length, 4, ' ', STR_PAD_LEFT).' | '.
        str_pad($line, 4, ' ', STR_PAD_LEFT).' | '.$value;
    }

    /**
     * Compare two Results
     *
     * @param Result $expectedResult
     * @param Result $actualResult
     *
     * @return string
     */
    public static function compareResults(Result $expectedResult, Result $actualResult)
    {
        $expectedIterator = new ArrayIterator($expectedResult->getTokens());
        $actualIterator   = new ArrayIterator($actualResult->getTokens());

        $values  = [];
        $longest = 0;

        while ($actualIterator->valid() || $expectedIterator->valid()) {
            $expected = '';
            $actual   = '';

            if ($expectedIterator->valid()) {
                $expected = (string) self::dumpToken($expectedIterator->current());
            }
            if ($actualIterator->valid()) {
                $actual = (string) self::dumpToken($actualIterator->current());
            }

            $values[] = [
                'actual'    => $actual,
                'expected'  => $expected,
                'missmatch' => (bool) ($actualIterator->current() !== $expectedIterator->current()),
            ];

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
        $comparision .= str_pad('expected ('.count($expectedResult).')', $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= ' | ';
        $comparision .= str_pad('actual('.count($actualResult).')', $longest + 2, ' ', STR_PAD_BOTH);
        $comparision .= PHP_EOL;
        $comparision .= PHP_EOL;
        $i = 0;
        foreach ($values as $val) {
            if (true === $val['missmatch']) {
                $comparision .= '####### NEXT IS DIFFERENT ##'.PHP_EOL;
            }
            $comparision .= str_pad($i.') ', 4, ' ');
            $comparision .= str_pad($val['expected'], $longest + 2, ' ');
            $comparision .= ' | ';
            $comparision .= str_pad($val['actual'], $longest + 2, ' ');
            $comparision .= PHP_EOL;
            $i++;
        }

        return $comparision;
    }
}
