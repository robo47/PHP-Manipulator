<?php

require_once 'PHP/Formatter/ContainerManipulator/Interface.php';

class PHP_Formatter_ContainerManipulator_UnifyCasts
implements PHP_Formatter_ContainerManipulator_Interface
{
    /**
     * Manipulate
     *
     * @param PHP_Formatter_TokenContainer $container
     * @param array $params
     * @return boolean
     */
    public function manipulate(PHP_Formatter_TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $options = array(
            T_INT_CAST => '(int)',
            T_BOOL_CAST => '(bool)',
            T_DOUBLE_CAST => '(double)',
            T_OBJECT_CAST => '(object)',
            T_STRING_CAST => '(string)',
            T_UNSET_CAST => '(unset)',
            T_ARRAY_CAST => '(array)',
        );

        // array_merge() won't work with integer-keys!
        foreach($params as $cast => $value) {
            $options[$cast] = $value;
        }
        $changed = false;

        while($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Formatter_Token */
            if($this->_isSearchedToken($token, array_keys($options))) {
                $newValue = $options[$token->getType()];
                if ($token->getValue() != $newValue) {
                    $token->setValue($newValue);
                    $changed = true;
                }
            }
            $iterator->next();
        }

        return $changed;
    }

    /**
     * @param PHP_Formatter_Token $token
     * @param array $searched
     * @return boolean
     * @todo refactor by creating a global abstract class with ->evaluateContainerConstraint, ...
     */
    protected function _isSearchedToken($token, $searched)
    {
        $constriant = new PHP_Formatter_TokenConstraint_IsType();
        return $constriant->evaluate($token, $searched);
    }
}