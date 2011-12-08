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
class ElseAndIfToElseif
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

        $waitingForIf = false;
        $replaceTokens = array();

        $allowedTypes = array(T_IF, T_ELSE, T_WHITESPACE);

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, T_ELSE)) {
                $waitingForIf = true;
                $replaceTokens = array();
            }
            if (true === $waitingForIf && !$this->isType($token, $allowedTypes)) {
                $waitingForIf = false;
            } else {
                $replaceTokens[] = $token;
            }

            if (true === $waitingForIf && $this->isType($token, T_IF)) {
                $waitingForIf = false;
                $token = array_pop($replaceTokens);
                $token->setType(T_ELSEIF);
                $token->setValue('elseif');

                $container->removeTokens($replaceTokens);

                $replaceTokens = array();
            }

            $iterator->next();
        }
        $container->retokenize();
    }
}