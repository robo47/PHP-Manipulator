<?php

namespace PHP\Manipulator;

use PHP\Manipulator\TokenContainer;

/**
 * @package PHP\Manipulator
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class FileContainer extends TokenContainer
{

    /**
     * @var string
     */
    protected $_file = '';

    /**
     * @param string $file
     * @throws Exception If file does not exist or is not readable
     */
    public function __construct($file)
    {
        $this->_file = $file;
        if (!file_exists($file) || !is_file($file) || !is_readable($file)) {
            throw new \Exception('Unable to open file for reading: ' . $file);
        }
        parent::__construct(file_get_contents($file));
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->_file;
    }

    /**
     * Save code to a file
     *
     * @param string $file
     * @throws Exception If file is not writeable
     */
    public function saveTo($file)
    {
        @touch($file);
        if (!is_writeable($file)) {
            throw new \Exception('Unable to open file for writing: ' . $file);
        }
        file_put_contents($file, $this->toString());
    }

    /**
     * Save code back to file
     *
     * @throws Exception If file is not writeable
     */
    public function save()
    {
        $this->saveTo($this->_file);
    }
}