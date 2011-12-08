<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \PHP\Manipulator\TokenConstraint\IsSinglelineComment
 * @uses    \PHP\Manipulator\TokenConstraint\IsMultilineComment
 * @uses    \PHP\Manipulator\TokenManipulator\RemoveCommentIndention
 */
class RemoveIndention extends Action
{

    /**
     * Unindents all Code
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $regexWhitespace = '[\t ]{1,}';
        $regexNotWhitespace = '[^\t^ ]{1,}';
        $linebreak = '\n|\r\n|\r';

        $previousToken = null;
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_WHITESPACE)) {
                $value = $token->getValue();

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
            $previousToken = $token;
            $iterator->next();
        }
        $container->retokenize();
    }
}