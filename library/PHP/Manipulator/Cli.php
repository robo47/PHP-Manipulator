<?php

namespace PHP\Manipulator;

use PHP\Manipulator;

class Cli
{

    /**
     *
     * @var float
     */
    protected $_start = - 1;

    /**
     * @var array
     */
    protected $_params = array();

    /**
     *
     * @var \ezcConsoleInput
     */
    protected $_input = null;

    /**
     *
     * @var \ezcConsoleOutput
     */
    protected $_output = null;

    /**
     *
     * @var array
     */
    protected $_options = null;

    /**
     *
     * @param array $params
     */
    public function __construct($params = array())
    {
        $this->_start = \microtime(true);
        $this->_params = $params;
        $this->_initConsole();
    }

    /**
     * Init Console with Options
     */
    protected function _initConsole()
    {
        $input = new \ezcConsoleInput();
        $output = new \ezcConsoleOutput();


        $options = $this->getConsoleOptions();
        $this->_options = $options;
        foreach($options as $option) {
            $input->registerOption($option);
        }

        $this->setConsoleInput($input);
        $this->setConsoleOutput($output);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @return array
     */
    public function getConsoleOptions()
    {
        $options = array();
        $path = __DIR__ .
                DIRECTORY_SEPARATOR . 'Cli' .
                DIRECTORY_SEPARATOR . 'Action' .
                DIRECTORY_SEPARATOR;
        $fileIterator = \File_Iterator_Factory::getFileIterator($path, '.php');

        foreach($fileIterator as $file) {
            /* @var $actionClass SplFileInfo */
            $actionClassname = substr($file->getFilename(), 0, -4);
            $action = $this->getAction($actionClassname);

            foreach($action->getConsoleOption() as $option) {
                $options[$actionClassname] = $option;
            }
        }

        return $options;
    }

    /**
     * Set console input
     *
     * @param ezcConsoleInput $input
     * @return \PHP\Manipulator\Cli *Returns Fluent Interface*
     */
    public function setConsoleInput(\ezcConsoleInput $input)
    {
        $this->_input = $input;
        return $this;
    }


    /**
     * Set console output
     *
     * @param ezcConsoleOutput $output
     * @return \PHP\Manipulator\Cli *Returns Fluent Interface*
     */
    public function setConsoleOutput(\ezcConsoleOutput $output)
    {
        $this->_output = $output;
        return $this;
    }

    /**
     *
     * @return ezcConsoleInput
     */
    public function getConsoleInput()
    {
        return $this->_input;
    }

    /**
     *
     * @return ezcConsoleOutput
     */
    public function getConsoleOutput()
    {
        return $this->_output;
    }

    /**
     *
     * @return float
     */
    public function getStartTime()
    {
        return $this->_start;
    }

    /**
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     *
     * @return string
     */
    public function getHeader()
    {
        return 'PHP Manipulator ' . Manipulator::VERSION . ' by Benjamin Steininger' . PHP_EOL;
    }

    /**
     * Get Footer
     *
     * @return string
     */
    public function getFooter()
    {
        $time = round(microtime(true) - $this->_start, 2);
        $footer = 'Time: ' . $time . 's' . PHP_EOL;
        $footer .= 'Memory: ' . round((memory_get_peak_usage() / (1024 * 1024)), 2) . 'mb' . PHP_EOL;
        return $footer;
    }

    /**
     * Run
     */
    public function run()
    {
        $output = $this->getConsoleOutput();
        $input = $this->getConsoleInput();

        $input->process($this->_params);

        $output->outputText($this->getHeader());

        $options = $this->getOptions();

        $actionName = false;
        foreach($options as $key => $option) {
            /* @var $option ezcConsoleOption */
            if (isset($option->value) && false != $option->value) {
                $actionName = $key;
                break;
            }
        }

        if (false === $actionName) {
            $actionName = 'help';
        }

        $action = $this->getAction(
            $actionName,
            $this->_params
        );
        $action->run();

        $output->outputText($this->getFooter());
    }

    /**
     * Get Action
     *
     * @param string $action
     * @param string $param
     * @return \PHP\Manipulator\Cli\Action
     */
    public function getAction($action)
    {
        if (!isset($this->_actions[$action])) {
            $class = "PHP\Manipulator\Cli\Action\\$action";
            $this->_actions[$action] = new $class($this);
        }
        return $this->_actions[$action];
    }
}