<?php
namespace PHP\Manipulator;

// @todo check how to auto-inject config in AHelper and co (a factory to cache and create rules, contraints and manipulators?) into all stuff ?
abstract class Config
{

    /**
     * Array with all Optipons
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Array with all Rules
     *
     * @var array
     */
    protected $_rules = array();

    /**
     * Array with all Files
     *
     * @var array
     */
    protected $_files = array();

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
        $this->_options['rulePrefix'] = '\PHP\Manipulator\Rule\\';
        $this->_options['rulesetPrefix'] = '\PHP\Manipulator\Ruleset\\';
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
     * Get Rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->_rules;
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
        $this->_files[] = realpath($file);
        return $this;
    }

    /**
     * Get Option
     *
     * @param string $name
     * @return mixed
     */
    protected function _getOption($name)
    {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        }
        throw new \Exception('something which should not happen, just happened ... world is shutting down');
    }

    /**
     * Add rule
     *
     * @param string $rule
     * @param string|null $prefix
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addRule($rule, $prefix = null, array $options = array())
    {
        if (null === $prefix) {
            $prefix = $this->_getOption('rulePrefix');
        }
        $classname = $prefix . $rule;
        $this->_rules[] = new $classname($options);
        return $this;
    }

    /**
     * Add Ruleset
     *
     * @param string $ruleset
     * @param string|null $prefix
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addRuleset($ruleset, $prefix = null)
    {
        if (null === $prefix) {
            $prefix = $this->_getOption('rulesetPrefix');
        }
        $classname = $prefix . $ruleset;
        $ruleset = new $classname();
        /* @var $ruleset \PHP\Manipulator\Ruleset */
        foreach ($ruleset->getRules() as $rule) {
            $this->_rules[] = $rule;
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
        if (false === $realpath || !\file_exists($realpath) || !\is_dir($realpath) || !\is_readable($realpath)) {
            throw new \Exception('Unable to open path: ' . $path);
        }
        $suffix = $this->_getOption('fileSuffix');
        $files = \File_Iterator_Factory::getFileIterator($realpath, $suffix);

        // @todo fix for bug!! when not doing it, first file is added two times
        \iterator_count($files);

        foreach ($files as $file) {
            $this->addFile((string) $file);
        }

        return $this;
    }

    /**
     * Add directory
     *
     * @param string $path
     * @return \PHP\Manipulator\Config *Provides Fluent Interface*
     */
    public function addIterator(\Iterator $iterator)
    {
        // @todo fix for bug!! when not doing it, first file is added two times
        \iterator_count($iterator);

        foreach ($iterator as $file) {
            if ($file instanceof \SplFileInfo) {
                $file = (string)$file;
            }
            $this->addFile((string) $file);
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
                $type = '\PHP\Manipulator\Config\Xml';
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

        if (!\file_exists($file) || !\is_file($file) || !\is_readable($file)) {
            throw new \Exception('Unable to read file: ' . $oldFile);
        }
        return \file_get_contents($file);
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
}