<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class ElseifToElseAndIf
extends Action
{

    /**
     * Run Action
     *
     * @param \PHP\Manipulator\TokenContainer $container
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_ELSEIF)) {
                $token->setType(T_ELSE);
                $token->setValue('else');
                $whitespaceToken = new Token(' ', T_WHITESPACE);
                $ifToken = new Token('if', T_IF);
                $container->insertTokenAfter($token, $whitespaceToken);
                $container->insertTokenAfter($whitespaceToken, $ifToken);
                $iterator = $container->getIterator();
                $iterator->seekToToken($ifToken);
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}
