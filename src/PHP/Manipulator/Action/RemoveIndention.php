<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\TokenManipulator\RemoveCommentIndention;

class RemoveIndention extends Action
{
    public function run(TokenContainer $container)
    {
        $iterator           = $container->getIterator();
        $regexWhitespace    = '[\t ]{1,}';
        $regexNotWhitespace = '[^\t^ ]{1,}';
        $linebreak          = '\n|\r\n|\r';

        /** @var Token $previousToken */
        $previousToken = null;

        $pattern1 = sprintf('~%s$~', $regexWhitespace);
        $pattern2 = sprintf('~(%s)%s$~', $linebreak, $regexWhitespace);
        $pattern3 = sprintf('~(%s)%s%s(.*?)(%s)~m', $linebreak, $regexWhitespace, $regexNotWhitespace, $linebreak);
        $pattern4 = sprintf('~(%s)%s%s(.*?)$~m', $linebreak, $regexWhitespace, $regexNotWhitespace);

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isWhitespace()) {
                $value = $token->getValue();

                // Single-line-Comments include a Linebreak at the end, so the whitespace not begins with a linebreak
                if ($previousToken->isSingleLineComment()) {
                    $value = preg_replace($pattern1, '\1', $value);
                }
                $value = preg_replace($pattern2, '\1', $value);
                $value = preg_replace($pattern3, '\1\2', $value);
                $value = preg_replace($pattern4, '\1\2', $value);

                $token->setValue($value);
            } elseif ($token->isMultilineComment()) {
                $this->manipulateToken(RemoveCommentIndention::class, $token);
            }
            $previousToken = $token;
            $iterator->next();
        }
        $container->retokenize();
    }
}
