<?php

namespace PHP;

use PHP\Manipulator\TokenContainer;
use PHP\Manipulator\Action;

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
     * Array with used actions
     *
     * @var array
     */
    protected $_actions = array();

    /**
     * Array with files
     *
     * @var array
     */
    protected $_files = array();

    /**
     *
     * @param array $actions
     */
    public function __construct(array $actions = array(), $files = null)
    {
        $this->addActions($actions);
        $this->addFiles($files);
    }

    /**
     *
     * @param \PHP\Manipulator\Action $action
     * @return \PHP\Manipulator *Provides Fluent Interface*
     */
    public function addAction(Action $action)
    {
        $this->_actions[] = $action;
        return $this;
    }

    /**
     *
     * @param array $actions
     * @return \PHP_Manipulator *Provides Fluent Interface*
     */
    public function addActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addAction($action);
        }
        return $this;
    }

    /**
     * Remove Action
     *
     * @param \PHP\Manipulator\Action $removeAction
     * @return \PHP\Manipulator *Provides Fluent Interface*
     */
    public function removeAction(Action $removeAction)
    {
        foreach ($this->_actions as $key => $action) {
            if ($action === $removeAction) {
                unset($this->_actions[$key]);
            }
        }
        return $this;
    }

    /**
     * Get Actions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->_actions;
    }

    /**
     *
     * @return \PHP_Manipulator *Provides Fluent Interface*
     */
    public function removeAllActions()
    {
        $this->_actions = array();
        return $this;
    }

    /**
     *
     * @param string $classname
     * @return \PHP_Manipulator *Provides Fluent Interface*
     */
    public function removeActionByClassname($classname)
    {
        foreach ($this->_actions as $key => $action) {
            if ($action instanceof $classname) {
                unset($this->_actions[$key]);
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