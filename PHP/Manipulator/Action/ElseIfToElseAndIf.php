<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class ElseIfToElseAndIf
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