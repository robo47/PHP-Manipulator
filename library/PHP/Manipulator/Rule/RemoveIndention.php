<?php

namespace PHP\Manipulator\Rule;

use PHP\Manipulator\Rule;
use PHP\Manipulator\TokenContainer;

class RemoveIndention extends Rule
{

    /**
     * Unindents all Code
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function apply(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $regexWhitespace = '[\t ]{1,}';
        $regexNotWhitespace = '[^\t^ ]{1,}';
        $linebreak = '\n|\r\n|\r';
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsType', $token, T_WHITESPACE)) {
                $value = $token->getValue();
                // @todo create RemoveWhitespaceIndention-TokenManipulator
                // Spaces and Tabs in Lines which are completly empty
                $previousToken = $container->getPreviousToken($token);

                // Single-line-Comments include a Linebreak at the end, so the whitespace not begins with a linebreak
                if ($this->evaluateConstraint('IsSinglelineComment', $previousToken, T_COMMENT)) {
                    $value = preg_replace('~' . $regexWhitespace . '$~', '\1', $value);
                }
                $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . '$~', '\1', $value);
                $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . $regexNotWhitespace . '(.*?)(' . $linebreak . ')~m', '\1\2', $value);
                $value = preg_replace('~(' . $linebreak . ')' . $regexWhitespace . $regexNotWhitespace . '(.*?)$~m', '\1\2', $value);

                $token->setValue($value);
            } else if ($this->evaluateConstraint('IsMultilineComment', $token)) {
                $this->manipulateToken('RemoveCommentIndention', $token);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}