<?php

namespace PHP\Manipulator;

use PHP\Manipulator;
use Symfony\Components\Console\Application;

class Cli extends Application
{

    /**
     * @var float
     */
    protected $_start = - 1;

    /**
     *
     * @param array $params
     */
    public function __construct()
    {
        $this->_start = microtime(true);
        parent::__construct('phpManipulator', Manipulator::VERSION);
        $this->_initApp();
    }

    /**
     * Init Console with Options
     */
    protected function _initApp()
    {
        $path = __DIR__ .
        DIRECTORY_SEPARATOR . 'Cli' .
        DIRECTORY_SEPARATOR . 'Command' .
        DIRECTORY_SEPARATOR;
        $fileIterator = \File_Iterator_Factory::getFileIterator($path, '.php');

        foreach ($fileIterator as $file) {
            /* @var $file SplFileInfo */
            $command = 'PHP\Manipulator\Cli\Command\\' . substr($file->getFilename(), 0, - 4);
            $this->addCommand(new $command);
        }
    }

    /**
     *
     * @return float
     */
    public function getStartTime()
    {
        return $this->_start;
    }

}