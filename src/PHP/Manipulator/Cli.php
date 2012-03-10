<?php

namespace PHP\Manipulator;

use PHP\Manipulator;
use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 */
class Cli extends Application
{
    /**
     * @param array $params
     */
    public function __construct()
    {
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
        $finder = new Finder();

        // @todo use *Command.php and rename Commands
        $fileIterator = $finder->files()
        ->name('*.php')
        ->in($path);

        foreach ($fileIterator as $file) {
            /* @var $file SplFileInfo */
            $command = 'PHP\\Manipulator\\Cli\\Command\\' . substr($file->getFilename(), 0, -4);
            $this->add(new $command);
        }
    }
}
