<?php

namespace PHP\Manipulator;

use Symfony\Component\Finder\Finder;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @uses    \Symfony\Components\Finder\Finder
 * @uses    \PHP\Manipulator\Action
 * @uses    \PHP\Manipulator\Actionset
 */
abstract class Config
{

    /**
     * Array with all Optipons
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Array with all Actions
     *
     * @var array
     */
    protected $_actions = array();

    /**
     * Array with all Files
     *
     * @var array
     */
    protected $_files = array();

    /**
     * Classloaders
     *
     * @var array
     */
    protected $_classLoaders = array();

    /**
     * @param mixed $config
     */
    public function __construct($data)
    {
        $this->_initDefaultOptions();
        $this->_initConfig($data);
    }

    protected function _initDefaultOptions()
    {
        $this->_options['actionPrefix'] = 'PHP\\Manipulator\\Action\\';
        $this->_options['actionsetPrefix'] = 'PHP\\Manipulator\\Actionset\\';
        $this->_options['fileSuffix'] = '.php';
        $this->_options['defaultNewline'] = "\n";
    }

    /**
     *Child-Classes implement it and read/parse their configs in there
     */
    abstract protected function _initConfig($data);

    /**
     * Get Options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Get Actions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->_actions;
    }

    /**
     * Get Files
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->_files;
    }

    /**
     * Add File
     *
     * @param string $file
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addFile($file)
    {
        $realPath = realpath($file);
        if (false === $realPath) {
            throw new \Exception('File ' . $file . ' not found');
        }
        $this->_files[] = $realPath;

        return $this;
    }

    /**
     * Get Option
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name)
    {
        if (!isset($this->_options[$name])) {
            $message = 'Option "' . $name . '" does not exist';
            throw new \Exception($message);
        }

        return $this->_options[$name];
    }

    /**
     * Add action
     *
     * @param string $action
     * @param string|null $prefix
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addAction($action, $prefix = null, array $options = array())
    {
        if (null === $prefix) {
            $prefix = $this->getOption('actionPrefix');
        }
        $classname = $prefix . $action;
        $this->_actions[] = new $classname($options);

        return $this;
    }

    /**
     * Add Actionset
     *
     * @param string $actionset
     * @param string|null $prefix
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addActionset($actionset, $prefix = null)
    {
        if (null === $prefix) {
            $prefix = $this->getOption('actionsetPrefix');
        }
        $classname = $prefix . $actionset;
        $actionset = new $classname();
        /* @var $actionset \PHP\Manipulator\Actionset */
        foreach ($actionset->getActions() as $action) {
            $this->_actions[] = $action;
        }

        return $this;
    }

    /**
     * Add directory
     *
     * @param string $path
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addDirectory($path)
    {
        $realpath = realpath($path);
        if (false === $realpath || !file_exists($realpath) || !is_dir($realpath) || !is_readable($realpath)) {
            throw new \Exception('Unable to open path: ' . $path);
        }
        $suffix = $this->getOption('fileSuffix');
        $finder = new Finder();
        $files = $finder->files()->name('*' . $suffix)->in($realpath);

        foreach ($files as $file) {
            $this->addFile((string) $file);
        }

        return $this;
    }

    /**
     * Add an iterator
     *
     * All it needs to do is return a string or SplFileInfo-object on current()
     *
     * @param string $path
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addIterator(\Iterator $iterator)
    {
        foreach ($iterator as $file) {
            // cast to string for \SplFileInfo
            $this->addFile((string)$file);
        }

        return $this;
    }

    /**
     * Factory
     *
     * @param string $type
     * @param string $data Path or Code
     * @param boolean $isFile
     */
    public static function factory($type, $data, $isFile = false)
    {
        if (true === $isFile) {
            $data = self::getFileContent($data);
        }
        switch (strtolower($type)) {
            case 'xml':
                $type = 'PHP\\Manipulator\\Config\\Xml';
                break;
            default:
                break;
        }

        return new $type($data);
    }

    /**
     * Get file content
     *
     * @param string $file
     * @return string
     */
    public static function getFileContent($file)
    {
        $oldFile = $file;
        $file = realpath($file);

        if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
            throw new \Exception('Unable to read file: ' . $oldFile);
        }

        return file_get_contents($file);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addOption($name, $value)
    {
        $this->_options[$name] = $value;

        return $this;
    }
    /**
     * @param string $name
     * @param string $path
     */
    public function addClassLoader($namespace, $path)
    {
        $this->_classLoaders[$namespace] = $path;
    }

    /**
     * @return array
     */
    public function getClassLoaders()
    {
        return $this->_classLoaders;
    }
}
