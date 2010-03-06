<?php

require_once 'PHP/Formatter/Rule/Interface.php';
require_once 'PHP/Formatter/Ruleset/Interface.php';

class PHP_Formatter
{
    /**
     * Array with used rules
     * 
     * @var array
     */
    protected $_rules = array();

    /**
     *
     * @param array $rules
     */
    public function __construct(array $rules = array())
    {
        $this->addRules($rules);
    }

    /**
     *
     * @param PHP_Formatter_Rule_Interface $rule
     * @return PHP_Formatter *Provides Fluent Interface*
     */
    public function addRule(PHP_Formatter_Rule_Interface $rule)
    {
        $this->_rules[] = $rule;
        return $this;
    }

    /**
     *
     * @param array $rules
     * @return PHP_Formatter *Provides Fluent Interface*
     */
    public function addRules(array $rules)
    {
        foreach($rules as $rule) {
            $this->addRule($rule);
        }
        return $this;
    }

    /**
     * Remove Rule
     * 
     * @param PHP_Formatter_Rule_Interface $removeRule
     * @return PHP_Formatter *Provides Fluent Interface*
     */
    public function removeRule(PHP_Formatter_Rule_Interface $removeRule)
    {
        foreach($this->_rules as $key => $rule) {
            if ($rule === $removeRule) {
                unset($this->_rules[$key]);
            }
        }
        return $this;
    }

    /**
     * Get Rules
     * 
     * @return array
     */
    public function getRules()
    {
        return $this->_rules;
    }

    /**
     *
     * @param PHP_Formatter_Ruleset_Interface $ruleset
     * @return PHP_Formatter *Provides Fluent Interface*
     */
    public function addRuleset(PHP_Formatter_Ruleset_Interface $ruleset)
    {
        $this->addRules($ruleset->getRules());
        return $this;
    }

    /**
     * Extracts the Rules from the ruleset and adds them to the formatters rules
     * 
     * @param array $rulesets
     * @return PHP_Formatter *Provides Fluent Interface*
     */
    public function addRulesets(array $rulesets)
    {
        foreach($rulesets as $ruleset) {
            $this->addRuleset($ruleset);
        }
        return $this;
    }

    /**
     *
     * @return PHP_Formatter *Provides Fluent Interface*
     */
    public function removeAllRules()
    {
        $this->_rules = array();
        return $this;
    }

    /**
     *
     * @param string $classname
     * @return PHP_Formatter *Provides Fluent Interface*
     */
    public function removeRuleByClassname($classname)
    {
        foreach($this->_rules as $key => $rule) {
            if ($rule instanceof $classname) {
                unset($this->_rules[$key]);
            }
        }
        return $this;
    }

    /**
     * Formats Code
     * 
     * @param string $code
     * @return string
     */
    public function formatCode($code)
    {
        $tokens = PHP_Formatter_TokenContainer::createFromCode($code);
        foreach($this->_rules as $rule) {
            /* @var $rule PHP_Formatter_Rule_Interface */
            $rule->applyRuleToTokens($tokens);
        }
        return $tokens->toString();
    }
}