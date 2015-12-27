<?php

namespace PHP;

use PHP\Manipulator\Action;
use SplFileInfo;

/**
 * @todo Should be the class used by apps not using the CLI ... really needed ?
 */
class Manipulator
{
    const VERSION = '2.0.0-DEV';

    /**
     * Array with used actions
     *
     * @var Action[]
     */
    private $actions = [];

    /**
     * Array with files
     *
     * @var string[]
     */
    private $files = [];

    /**
     * @param Action[]               $actions
     * @param string[]|SplFileInfo[] $files
     */
    public function __construct(array $actions = [], array $files = [])
    {
        $this->addActions($actions);
        $this->addFiles($files);
    }

    /**
     * @param Action $action
     *
     * @return Manipulator
     */
    public function addAction(Action $action)
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * @param Action[] $actions
     *
     * @return Manipulator
     */
    public function addActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addAction($action);
        }

        return $this;
    }

    /**
     * @param Action $removeAction
     *
     * @return Manipulator
     */
    public function removeAction(Action $removeAction)
    {
        foreach ($this->actions as $key => $action) {
            if ($action === $removeAction) {
                unset($this->actions[$key]);
            }
        }

        return $this;
    }

    /**
     * @return Action[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return Manipulator
     */
    public function removeAllActions()
    {
        $this->actions = [];

        return $this;
    }

    /**
     * @param string $classname
     *
     * @return Manipulator
     */
    public function removeActionByClassname($classname)
    {
        foreach ($this->actions as $key => $action) {
            if ($action instanceof $classname) {
                unset($this->actions[$key]);
            }
        }

        return $this;
    }

    /**
     * Add a file or files (array, iterator [as long as it items are strings or implement __toString())]
     *
     * @param string[]|SplFileInfo[] $files
     *
     * @return Manipulator
     */
    public function addFiles($files)
    {
        foreach ($files as $file) {
            // string-cast if it is something else (SplFileInfo)
            $this->addFile((string) $file);
        }

        return $this;
    }

    /**
     * @param string $file
     *
     * @return Manipulator
     */
    public function addFile($file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return Manipulator
     */
    public function removeAllFiles()
    {
        $this->files = [];

        return $this;
    }
}
