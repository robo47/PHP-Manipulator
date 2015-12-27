<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Helper\NewlineDetector;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class StripUnneededPhpCloseTag extends Action
{
    const OPTION_STRIP_WHITESPACE_FROM_END = 'stripWhitespaceFromEnd';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_STRIP_WHITESPACE_FROM_END)) {
            $this->setOption(self::OPTION_STRIP_WHITESPACE_FROM_END, false);
        }
    }

    public function run(TokenContainer $container)
    {
        $stripWhitespaceFromEnd = $this->getOption(self::OPTION_STRIP_WHITESPACE_FROM_END);

        $iterator = $container->getReverseIterator();
        $helper   = new NewlineDetector("\n");

        while ($iterator->valid()) {
            $token = $iterator->current();

            if (!$this->isNotAllowedTag($token)) {
                break;
            }
            if ($token->isType(T_CLOSE_TAG)) {
                if ($token->endsWithNewline()) {
                    $newline = $helper->getNewlineFromToken($token);
                    $token->setType(T_WHITESPACE);
                    $token->setValue($newline);
                } else {
                    $container->removeToken($token);
                }
                break;
            }
            $iterator->next();
        }
        $container->retokenize();
        if (true === $stripWhitespaceFromEnd) {
            $this->runAction(RemoveWhitespaceFromEnd::class, $container);
        }
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    private function isNotAllowedTag(Token $token)
    {
        return $token->isType(T_CLOSE_TAG) || $token->isWhitespace() || $token->containsOnlyWhitespace();
    }
}
