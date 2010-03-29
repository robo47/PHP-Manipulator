<?php

class PHP_Formatter_Autoloader
{
    /**
     *
     * @param string $classname
     */
    public function autoload($classname)
    {
        $filename = str_replace('_', '/', $classname) . '.php';
        include $filename;
    }

    /**
     *
     */
    public static function register()
    {
        $autoloader = new PHP_Formatter_Autoloader();
        spl_autoload_register(array($autoloader, 'autoload'));
    }
}