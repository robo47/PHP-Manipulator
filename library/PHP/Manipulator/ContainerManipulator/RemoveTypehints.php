<?php

class PHP_Manipulator_ContainerManipulator_RemoveTypehints
extends PHP_Manipulator_ContainerManipulator_Abstract
{

    /**
     * Manipulate Container
     *
     * @param PHP_Manipulator_TokenContainer $container
     * @param mixed $params
     */
    public function manipulate(PHP_Manipulator_TokenContainer $container, $params = null)
    {
        $iterator = $container->getIterator();

        $functionTokens = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Manipulator_Token */
            if ($this->evaluateConstraint('IsType', $token, T_FUNCTION)) {
                $functionTokens[] = $token;
            }
            $iterator->next();
        }
        foreach($functionTokens as $token) {
            $this->_parseFunctionArguments($container, $token);
        }
        $container->retokenize();
    }

    /**
     *
     * @param PHP_Manipulator_TokenContainer $container
     * @param PHP_Manipulator_Token $token
     */
    protected function _parseFunctionArguments(PHP_Manipulator_TokenContainer $container, PHP_Manipulator_Token $startToken)
    {
        $argumentTokens = array();
        $iterator = $container->getIterator();
        $iterator->seek($container->getOffsetByToken($startToken));
        $indentionLevel = 0;
        $inside = false;
        $arguments = array();
        $argument = array();
        while ($iterator->valid()) {
            $token = $iterator->current();
            /* @var $token PHP_Manipulator_Token */
            if ($this->evaluateConstraint('IsOpeningBrace', $token)) {
                $indentionLevel++;
            }
            if ($indentionLevel) {
                $argumentTokens[] = $token;
            }
            if ($this->evaluateConstraint('IsClosingBrace', $token)) {
                $indentionLevel--;
            }
            if ($token->getValue() == ',' && $token->getType() === null) {
                $arguments[] = $argument;
                $argument = array();
            } elseif($inside) {
                $argument[] = $token;
            }
            if ($indentionLevel > 0) {
                $inside = true;

            }
            if ($indentionLevel == 0 && $inside) {
                break;
            }
            $iterator->next();
        }
        foreach($arguments as $argument) {
            $typeHint = $this->_parseSingleArgument($argument);
            if ($typeHint instanceof PHP_Manipulator_Token) {
                $container->removeToken($typeHint);
            }
        }
    }

    /**
     *
     * @param PHP_Manipulator_TokenContainer $container
     * @param array $argumentTokens
     */
    protected function _parseSingleArgument(array $argumentTokens)
    {
        $typehintToken = null;
        foreach($argumentTokens as $token) {
            /* @var $token PHP_Manipulator_Token */
            if ($this->evaluateConstraint('IsType', $token, T_STRING)) {
                $typehintToken = $token;
                break;
            }
        }
        return $typehintToken;
    }
}