<?php

namespace PHP\Manipulator;

// @todo have a look at http://de3.php.net/manual/en/function.stream-resolve-include-path.php would require php 5.3.2 at least
class Autoloader
{
    /**
     * @param string $classname
     */
    public function autoload($classname)
    {
        // Remove leading \\ in case of fully qualified namespace ?
        if ($classname[0] === '\\') {
            $classname = \substr($classname, 1);
        }
        $filename = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        $filename = str_replace('_', DIRECTORY_SEPARATOR, $filename);

        include $filename . '.php';
    }

    /**
     * Registers the autoloader
     */
    public static function register()
    {
        $autoloader = new Autoloader();
        spl_autoload_register(array($autoloader, 'autoload'));
    }
}