<?php

namespace PHP;

use PHP\Manipulator\IRule;
use PHP\Manipulator\TokenContainer;

class Manipulator
{

    /**
     * Version number
     */
    const VERSION = '@version@';

    /**
     * Array with used rules
     *
     * @var array
     */
    protected $_rules = array();

    /**
     * Array with files
     *
     * @var array
     */
    protected $_files = array();

    /**
     *
     * @param array $rules
     */
    public function __construct(array $rules = array(), $files = null)
    {
        $this->addRules($rules);
        $this->addFiles($files);
    }

    /**
     *
     * @param PHP\Manipulator\IRule $rule
     * @return \PHP\Manipulator *Provides Fluent Interface*
     */
    public function addRule(IRule $rule)
    {
        $this->_rules[] = $rule;
        return $this;
    }

    /**
     *
     * @param array $rules
     * @return \PHP_Manipulator *Provides Fluent Interface*
     */
    public function addRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
        return $this;
    }

    /**
     * Remove Rule
     *
     * @param PHP\Manipulator\IRule $removeRule
     * @return \PHP\Manipulator *Provides Fluent Interface*
     */
    public function removeRule(IRule $removeRule)
    {
        foreach ($this->_rules as $key => $rule) {
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
     * @return \PHP_Manipulator *Provides Fluent Interface*
     */
    public function removeAllRules()
    {
        $this->_rules = array();
        return $this;
    }

    /**
     *
     * @param string $classname
     * @return \PHP_Manipulator *Provides Fluent Interface*
     */
    public function removeRuleByClassname($classname)
    {
        foreach ($this->_rules as $key => $rule) {
            if ($rule instanceof $classname) {
                unset($this->_rules[$key]);
            }
        }
        return $this;
    }

    /**
     * Add a file or files (array, iterator [as long as it items are strings or implement __toString())]
     *
     * @param array|Iterator|string $files
     */
    public function addFiles($files)
    {
        if ($files instanceof \Iterator || is_array($files)) {
            foreach($files as $file) {
                // string-cast if it is something else (SplFileInfo)
                $this->addFile((string)$file);
            }
        } elseif(is_string($files)) {
            $this->addFile($files);
        }
        return $this;
    }

    /**
     *
     * @param string $file
     */
    public function addFile($file)
    {
        $this->_files[] = $file;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->_files;
    }

    /**
     *
     */
    public function removeAllFiles()
    {
        $this->_files = array();
        return $this;
    }
}