<?php

class PHP_Formatter_Util
{

    /**
     * Dump Container
     *
     * @param PHP_Formatter_TokenContainer $container
     * @return string
     */
    public static function dumpContainer(PHP_Formatter_TokenContainer $container)
    {
        $dump = '';
        $iterator = $container->getIterator();
        $dump .= str_pad('Token', 28, ' ', STR_PAD_RIGHT) . '| ' . str_pad('LEN', 4, ' ', STR_PAD_LEFT) . ' | ' . str_pad('LINE', 4, ' ', STR_PAD_LEFT) . ' | VALUE' . PHP_EOL . PHP_EOL;

        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            $dump .= PHP_Formatter_Util::dumpToken($token);
            $iterator->next();
        }

        return trim($dump);
    }

    /**
     * Compare Containers
     *
     * Returns string-presentation of both containers next to each other
     *
     * @param PHP_Formatter_TokenContainer $first
     * @param PHP_Formatter_TokenContainer $secod
     */
    public static function compareContainers(PHP_Formatter_TokenContainer $first,
        PHP_Formatter_TokenContainer $second)
    {
        $firstDump = PHP_Formatter_Util::dumpContainer($first);
        $secondDump = PHP_Formatter_Util::dumpContainer($second);

        $firstDumpAsArray = preg_split('~(\n|\r\n|\r)~', $firstDump);
        $secondDumpAsArray = preg_split('~(\n|\r\n|\r)~', $secondDump);

        $iter1 = new ArrayIterator($firstDumpAsArray);
        $iter2 = new ArrayIterator($secondDumpAsArray);

        $length = PHP_Formatter_Util::getLongestLineLength($firstDumpAsArray);

        $c1Count = (count($iter1) - 2);
        $c2Count = (count($iter2) - 2);

        $code = '';
        $code .= str_pad('Tokens: ' . $c1Count, ($length + 6), ' ', STR_PAD_BOTH) . ' |';
        $code .= str_pad('Tokens: ' . $c2Count, ($length + 6), ' ', STR_PAD_BOTH);
        $code .= PHP_EOL;

        $i = 1;
        while ($iter1->valid() || $iter2->valid()) {

            $line1 = '';
            $line2 = '';

            if ($iter1->valid()) {
                $line1 = (string) $iter1->current();
                $iter1->next();
            }

            if ($iter2->valid()) {
                $line2 = (string) $iter2->current();
                $iter2->next();
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
     * @param PHP_Formatter_Token $token
     * @return string
     */
    public static function dumpToken(PHP_Formatter_Token $token)
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
        return str_pad($typeName, 28, ' ', STR_PAD_RIGHT) . '| ' . str_pad($length, 4, ' ', STR_PAD_LEFT) . ' | ' . str_pad($line, 4, ' ', STR_PAD_LEFT) . ' | ' . $value . PHP_EOL;
    }
}