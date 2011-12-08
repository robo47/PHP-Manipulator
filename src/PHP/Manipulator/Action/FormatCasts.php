<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class FormatCasts
extends Action
{
    public function init()
    {
        if (!$this->hasOption('searchedTokens')) {
            $this->setOption(
                'searchedTokens',
                array(
                    T_INT_CAST => '(int)',
                    T_BOOL_CAST => '(bool)',
                    T_DOUBLE_CAST => '(double)',
                    T_OBJECT_CAST => '(object)',
                    T_STRING_CAST => '(string)',
                    T_UNSET_CAST => '(unset)',
                    T_ARRAY_CAST => '(array)',
                )
            );
        }
        if (!$this->hasOption('whitespaceBehindCasts')) {
            $this->setOption('whitespaceBehindCasts', '');
        }
    }

    /**
     * Format casts
     *
     * @param \PHP\Manipulator\TokenContainer $container
     * @param array $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();
        $searchedTokens = $this->getOption('searchedTokens');
        $whitespace = $this->getOption('whitespaceBehindCasts');

        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->isType($token, array_keys($searchedTokens))) {
                $token->setValue($searchedTokens[$token->getType()]);
                $next = $iterator->getNext();
                if ($this->isType($next, T_WHITESPACE)) {
                    if ($next->getValue() != $whitespace) {
                        $next->setValue($this->getOption('whitespaceBehindCasts'));
                    }
                } else if (!empty($whitespace)) {
                    $container->insertTokenAfter($token, new Token($whitespace, T_WHITESPACE));
                    $iterator->update($token);
                }
            }
            $iterator->next();
        }
        $container->retokenize();
    }
}