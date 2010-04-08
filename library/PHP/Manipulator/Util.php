<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Token;

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
     * @param \PHP\Manipulator\TokenContainer $first
     * @param \PHP\Manipulator\TokenContainer $secod
     */
    public static function compareContainers(TokenContainer $first,
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
    public static function dumpToken(Token $token)
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
        return str_pad($typeName, 28, ' ', STR_PAD_RIGHT) . '| ' .
            str_pad($length, 4, ' ', STR_PAD_LEFT) . ' | ' .
            str_pad($line, 4, ' ', STR_PAD_LEFT) . ' | ' . $value . PHP_EOL;
    }
}