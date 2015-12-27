<?php

namespace PHP\Manipulator;

use Iterator;
use PHP\Manipulator\Config\XmlConfig;
use PHP\Manipulator\Exception\ConfigException;
use Symfony\Component\Finder\Finder;

abstract class Config
{
    /**
     * @var mixed[]
     */
    private $options = [];

    /**
     * @var Action[]
     */
    private $actions = [];

    /**
     * @var string[]
     */
    private $files = [];

    /**
     * @var string[]
     */
    private $classLoaders = [];

    /**
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->initDefaultOptions();
        $this->initConfig($data);
    }

    private function initDefaultOptions()
    {
        $this->options['actionPrefix']    = 'PHP\\Manipulator\\Action\\';
        $this->options['actionsetPrefix'] = 'PHP\\Manipulator\\Actionset\\';
        $this->options['fileSuffix']      = '.php';
        $this->options['defaultNewline']  = "\n";
    }

    /**
     * @param mixed $data
     */
    abstract protected function initConfig($data);

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return Action[]
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return string[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $file
     *
     * @return Config
     */
    public function addFile($file)
    {
        $realPath = realpath($file);
        if (false === $realPath) {
            $message = sprintf('File "%s" not found', $file);
            throw new ConfigException($message, ConfigException::FILE_NOT_FOUND);
        }
        $this->files[] = $realPath;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            $message = sprintf('Option "%s" does not exist', $name);
            throw new ConfigException($message, ConfigException::OPTION_NOT_FOUND);
        }

        return $this->options[$name];
    }

    /**
     * @param string      $action
     * @param string|null $prefix
     * @param mixed[]     $options
     *
     * @return Config
     */
    public function addAction($action, $prefix = null, array $options = [])
    {
        if (null === $prefix) {
            $prefix = $this->getOption('actionPrefix');
        }
        $classname       = $prefix.$action;
        $this->actions[] = new $classname($options);

        return $this;
    }

    /**
     * @param string      $actionset
     * @param string|null $prefix
     *
     * @return Config
     */
    public function addActionset($actionset, $prefix = null)
    {
        if (null === $prefix) {
            $prefix = $this->getOption('actionsetPrefix');
        }
        $classname = $prefix.$actionset;
        $actionset = new $classname();
        /* @var $actionset \PHP\Manipulator\Actionset */
        foreach ($actionset->getActions() as $action) {
            $this->actions[] = $action;
        }

        return $this;
    }

    /**
     * @param string $path
     *
     * @return Config
     */
    public function addDirectory($path)
    {
        if (!file_exists($path) || !is_dir($path) || !is_readable($path)) {
            $message = sprintf('Unable to open path %s', $path);
            throw new ConfigException($message, ConfigException::UNABLE_TO_OPEN_PATH);
        }
        $suffix = $this->getOption('fileSuffix');
        $files  = Finder::create()->files()
                       ->name(sprintf('*%s', $suffix))
                       ->in($path);

        foreach ($files as $file) {
            $this->addFile((string) $file);
        }

        return $this;
    }

    /**
     * @param Iterator $iterator
     *
     * @return Config
     */
    public function addIterator(Iterator $iterator)
    {
        foreach ($iterator as $file) {
            // cast to string for SplFileInfo
            $this->addFile((string) $file);
        }

        return $this;
    }

    /**
     * @param string $type
     * @param string $data   Path or Code
     * @param bool   $isFile
     */
    public static function factory($type, $data, $isFile = false)
    {
        if (true === $isFile) {
            $data = self::getFileContent($data);
        }
        switch (strtolower($type)) {
            case 'xml':
                $type = XmlConfig::class;
                break;
            default:
                break;
        }

        return new $type($data);
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public static function getFileContent($file)
    {
        $oldFile = $file;
        $file    = realpath($file);

        if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
            $message = sprintf('Unable to read file:  %s', $oldFile);
            throw new ConfigException($message, ConfigException::UNABLE_TO_READ_FILE);
        }

        return file_get_contents($file);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return Config
     */
    public function addOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * @param string $namespace
     * @param string $path
     */
    public function addClassLoader($namespace, $path)
    {
        $this->classLoaders[$namespace] = $path;
    }

    /**
     * @return string[]
     */
    public function getClassLoaders()
    {
        return $this->classLoaders;
    }
}
