<?php

namespace PHP\Manipulator;

use PHP\Manipulator;
use Symfony\Components\Console\Application;
use Symfony\Components\Finder\Finder;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class Cli extends Application
{

    /**
     * @var float
     */
    protected $_start = - 1;

    /**
     * @param array $params
     */
    public function __construct()
    {
        $this->_start = microtime(true);
        parent::__construct('phpManipulator', Manipulator::VERSION . ' (' . Manipulator::GITHASH . ')');
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
        $fileIterator = $finder->files()->name('*.php')->in($path);

        foreach ($fileIterator as $file) {
            /* @var $file SplFileInfo */
            $command = 'PHP\\Manipulator\\Cli\\Command\\' . substr($file->getFilename(), 0, -4);
            $this->addCommand(new $command);
        }
    }

    /**
     * @return float
     */
    public function getStartTime()
    {
        return $this->_start;
    }

}