<?php

namespace PHP\Manipulator;

use PHP\Manipulator;
use SplFileInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

class Cli extends Application
{
    public function __construct()
    {
        parent::__construct('phpManipulator', Manipulator::VERSION);
        $this->initApp();
    }

    /**
     * Init Console with Options
     */
    private function initApp()
    {
        $path = __DIR__.
            DIRECTORY_SEPARATOR.'Cli'.
            DIRECTORY_SEPARATOR.'Command'.
            DIRECTORY_SEPARATOR;
        $finder = new Finder();

        // @todo use *Command.php and rename Commands
        $fileIterator = $finder->files()
                               ->name('*.php')
                               ->in($path);

        foreach ($fileIterator as $file) {
            /* @var $file SplFileInfo */
            $command = 'PHP\\Manipulator\\Cli\\Command\\'.substr($file->getFilename(), 0, -4);
            $this->add(new $command());
        }
    }
}
