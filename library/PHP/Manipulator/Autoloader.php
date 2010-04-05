<?php

namespace PHP\Manipulator;

class Autoloader
{

    /**
     * @param string $classname
     */
    public function autoload($classname)
    {
        $filename = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        $filename = str_replace('_', DIRECTORY_SEPARATOR, $filename);
        include $filename . '.php';
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