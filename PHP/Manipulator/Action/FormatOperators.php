<?php

namespace PHP\Manipulator\Action;

use PHP\Manipulator\Action;

use PHP\Manipulator\Helper\SetWhitespaceAfterToken;
use PHP\Manipulator\Helper\SetWhitespaceBeforeToken;
use PHP\Manipulator\Token;
use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class FormatOperators
extends Action
{

    public function init()
    {
        if (!$this->hasOption('beforeOperator')) {
            $this->setOption(
                'beforeOperator',
                array (
                    // assignment operators
                    '=' => ' ',
                    T_AND_EQUAL => ' ',
                    T_CONCAT_EQUAL => ' ',
                    T_DIV_EQUAL => ' ',
                    T_MINUS_EQUAL => ' ',
                    T_MOD_EQUAL => ' ',
                    T_MUL_EQUAL => ' ',
                    T_OR_EQUAL => ' ',
                    T_PLUS_EQUAL => ' ',
                    T_SR_EQUAL => ' ',
                    T_SL_EQUAL => ' ',
                    T_XOR_EQUAL => ' ',

                    // logical operators
                    T_LOGICAL_AND => ' ',
                    T_LOGICAL_OR => ' ',
                    T_LOGICAL_XOR => ' ',
                    T_BOOLEAN_AND => ' ',
                    T_BOOLEAN_OR => ' ',

                    // bitwise operators
                    T_SL => ' ',
                    T_SR => ' ',

                    // incrementing/decrementing operators
                    T_DEC => '',
                    T_INC => '',

                    // comparision operators
                    T_IS_EQUAL => ' ',
                    T_IS_GREATER_OR_EQUAL => ' ',
                    T_IS_IDENTICAL => ' ',
                    T_IS_NOT_EQUAL => ' ',
                    T_IS_NOT_IDENTICAL => ' ',
                    T_IS_SMALLER_OR_EQUAL => ' ',

                    // type-operators
                    T_INSTANCEOF => ' ',
                )
            );
        }
        if (!$this->hasOption('afterOperator')) {
            $this->setOption(
                'afterOperator',
                array (
                    // assignment operators
                    '=' => ' ',
                    T_AND_EQUAL => ' ',
                    T_CONCAT_EQUAL => ' ',
                    T_DIV_EQUAL => ' ',
                    T_MINUS_EQUAL => ' ',
                    T_MOD_EQUAL => ' ',
                    T_MUL_EQUAL => ' ',
                    T_OR_EQUAL => ' ',
                    T_PLUS_EQUAL => ' ',
                    T_SR_EQUAL => ' ',
                    T_SL_EQUAL => ' ',
                    T_XOR_EQUAL => ' ',

                    // logical operators
                    T_LOGICAL_AND => ' ',
                    T_LOGICAL_OR => ' ',
                    T_LOGICAL_XOR => ' ',
                    T_BOOLEAN_AND => ' ',
                    T_BOOLEAN_OR => ' ',

                    // bitwise operators
                    T_SL => ' ',
                    T_SR => ' ',

                    // incrementing/decrementing operators
                    T_DEC => '',
                    T_INC => '',

                    // comparision operators
                    T_IS_EQUAL => ' ',
                    T_IS_GREATER_OR_EQUAL => ' ',
                    T_IS_IDENTICAL => ' ',
                    T_IS_NOT_EQUAL => ' ',
                    T_IS_NOT_IDENTICAL => ' ',
                    T_IS_SMALLER_OR_EQUAL => ' ',

                    // type-operators
                    T_INSTANCEOF => ' ',
                )
            );
        }
    }

    /**
     * @param \PHP\Manipulator\TokenContainer $container
     * @param mixed $params
     */
    public function run(TokenContainer $container)
    {
        $iterator = $container->getIterator();

        $operatorTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            if ($this->evaluateConstraint('IsOperator', $token)) {
                $operatorTokens[] = $token;
            }
            $iterator->next();
        }

        // since changing container can break iterator, we need to do actions here
        $params = array(
            'tokens' => $operatorTokens,
            'whitespace' => $this->getOption('afterOperator'),
        );
        $setWhitespaceAfterToken = new SetWhitespaceAfterToken();
        $setWhitespaceAfterToken->run($container, $params);

        $params = array(
            'tokens' => $operatorTokens,
            'whitespace' => $this->getOption('beforeOperator'),
        );
        $setWhitespaceBeforeToken = new SetWhitespaceBeforeToken();
        $setWhitespaceBeforeToken->run($container, $params);
    }
}