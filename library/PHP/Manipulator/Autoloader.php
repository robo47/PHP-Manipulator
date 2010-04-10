<?php

namespace PHP\Manipulator;

class Autoloader
{

    /**
     * Loads the ezc-Autoloader-Class
     */
    public function __construct()
    {
        require_once 'ezc/Base/base.php';
    }

    /**
     * @param string $classname
     */
    public function autoload($classname)
    {
        if ($this->_isEzcClass($classname)) {

            \ezcBase::autoload($classname);
            return true;
        }

        $filename = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        $filename = str_replace('_', DIRECTORY_SEPARATOR, $filename);
        if ($filename[0] === \DIRECTORY_SEPARATOR) {
            $filename = \substr($filename, 1);
        }
        include $filename . '.php';
    }

    /**
     *
     * @param string $classname
     * @return boolean
     */
    protected function _isEzcClass($classname)
    {
        return false !== \strpos($classname, 'ezc') || false !== \strpos($classname, '\ezc');
    }

    /**
     *
     */
    public static function register()
    {
        $autoloader = new Autoloader();
        spl_autoload_register(array($autoloader, 'autoload'));
    }
}