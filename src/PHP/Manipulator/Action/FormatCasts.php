<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

class FormatCasts extends Action
{
    const OPTION_SEARCHED_TOKENS = 'searchedTokens';

    const OPTION_WHITESPACE_BEHIND_CASTS = 'whitespaceBehindCasts';

    public function init()
    {
        if (!$this->hasOption(self::OPTION_SEARCHED_TOKENS)) {
            $this->setOption(
                self::OPTION_SEARCHED_TOKENS,
                [
                    T_INT_CAST    => '(int)',
                    T_BOOL_CAST   => '(bool)',
                    T_DOUBLE_CAST => '(double)',
                    T_OBJECT_CAST => '(object)',
                    T_STRING_CAST => '(string)',
                    T_UNSET_CAST  => '(unset)',
                    T_ARRAY_CAST  => '(array)',
                ]
            );
        }
        if (!$this->hasOption(self::OPTION_WHITESPACE_BEHIND_CASTS)) {
            $this->setOption(self::OPTION_WHITESPACE_BEHIND_CASTS, '');
        }
    }

    public function run(TokenContainer $container)
    {
        $iterator       = $container->getIterator();
        $searchedTokens = $this->getOption(self::OPTION_SEARCHED_TOKENS);
        $whitespace     = $this->getOption(self::OPTION_WHITESPACE_BEHIND_CASTS);

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($token->isType(array_keys($searchedTokens))) {
                $token->setValue($searchedTokens[$token->getType()]);
                $next = $iterator->getNext();
                if ($next->isWhitespace()) {
                    if ($next->getValue() !== $whitespace) {
                        $next->setValue($this->getOption(self::OPTION_WHITESPACE_BEHIND_CASTS));
                    }
                } elseif (!empty($whitespace)) {
                    $container->insertTokenAfter($token, Token::createFromValueAndType($whitespace, T_WHITESPACE));
                    $iterator->update($token);
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
