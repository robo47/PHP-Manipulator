<?php

namespace PHP;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Rule;

// @todo check if this class is really needed for anything elsethan version and githash ... seems like it does not really offer anything
// @todo add Support for iterators and stuff
// @todo Should be the class used by apps not using the CLI
class Manipulator
{

    /**
     * Version number
     */
    const VERSION = '@version@';

    /**
     * Git commit-hash
     */
    const GITHASH = '@githash@';

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
     * @param \PHP\Manipulator\Rule $rule
     * @return \PHP\Manipulator *Provides Fluent Interface*
     */
    public function addRule(Rule $rule)
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
     * @param \PHP\Manipulator\Rule $removeRule
     * @return \PHP\Manipulator *Provides Fluent Interface*
     */
    public function removeRule(Rule $removeRule)
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
            foreach ($files as $file) {
                // string-cast if it is something else (SplFileInfo)
                $this->addFile((string) $file);
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