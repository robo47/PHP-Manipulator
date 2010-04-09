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

        $output->formats->success->color = 'green';
        $output->formats->failure->color = 'red';


        $options = $this->getConsoleOptions();
        $this->_options = $options;
        foreach($options as $option) {
            if(is_array($option)) {
                foreach($option as $subOption) {
                    $input->registerOption($subOption);
                }
            } else {
                $input->registerOption($option);
            }
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

            $options[$actionClassname] = array();
            foreach($action->getConsoleOption() as $option) {
                $options[$actionClassname][] = $option;
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
        return 'PHP Manipulator ' . Manipulator::VERSION . PHP_EOL;
    }

    /**
     * Run
     */
    public function run()
    {
        $output = $this->getConsoleOutput();
        $input = $this->getConsoleInput();

        try {
            $input->process($this->_params);
            $output->outputText($this->getHeader());

            $options = $this->getOptions();

            $actionName = false;
            foreach($options as $key => $option) {
                if (is_array($option)) {
                    foreach($option as $suboption) {
                        if (false != $suboption->value) {
                            $actionName = $key;
                            break;
                        }
                    }
                } else {
                    /* @var $option ezcConsoleOption */
                    if (false != $option->value) {
                        $actionName = $key;
                        break;
                    }
                }
            }

            if (false === $actionName) {
                $actionName = 'help';
            }

            $action = $this->getAction(
                $actionName,
                $this->_params
            );

            $output->outputLine();
            $action->run();
            $output->outputLine();

            if ($actionName !== 'Stats' && false !== $input->getOption('stats')->value) {
                $action = $this->getAction(
                    'Stats',
                    $this->_params
                );
                $action->run();
            }
        } catch(\ezcConsoleException $e) {
            $output->outputText('something with ezcConsole fucked up: ' . $e->getMessage(), 'failure');
        } catch(\Exception $e) {
            $output->outputText('something else fucked up: ' . $e->getMessage(), 'failure');
        }
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