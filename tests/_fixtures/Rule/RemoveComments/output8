<?php



class Robo47_ErrorHandler
{

    
    protected $_oldErrorHandler = null;
    
    protected $_isErrorHandler = false;
    
    protected $_log = null;
    
    protected $_logCategory = 'errorHandler';
    
    protected $_errorPriorityMapping = array(
        E_ERROR => 3, 
        E_WARNING => 4, 
        E_NOTICE => 5, 
        E_USER_ERROR => 3, 
        E_USER_WARNING => 4, 
        E_USER_NOTICE => 5, 
        E_CORE_ERROR => 3, 
        E_CORE_WARNING => 4, 
        E_STRICT => 3, 
        E_RECOVERABLE_ERROR => 3, 
        'unknown' => 0, 

    );

    
    public function __construct($log = null, $category = 'errorHandler')
    {
        $this->setLog($log);
        $this->setLogCategory($category);
    }

    
    public function setLog(Zend_Log $log = null)
    {
        $this->_log = $log;
        return $this;
    }

    
    public function getLog()
    {
        return $this->_log;
    }

    
    public function setLogCategory($category)
    {
        $this->_logCategory = $category;
        return $this;
    }

    
    public function getLogCategory()
    {
        return $this->_logCategory;
    }

    
    protected function _getErrorsPriority($error)
    {
        if (isset($this->_errorPriorityMapping[$error])) {
            return $this->_errorPriorityMapping[$error];
        } else {
            return $this->_errorPriorityMapping['unknown'];
        }
    }

    
    public function setErrorPriorityMapping(array $errorPriorityMapping)
    {
        $this->_errorPriorityMapping = $errorPriorityMapping;
        if (!isset($this->_errorPriorityMapping['unknown'])) {
            $this->_errorPriorityMapping['unknown'] = 0;
        }
        return $this;
    }

    
    public function getErrorPriorityMapping()
    {
        return $this->_errorPriorityMapping;
    }

    
    protected function _logError($errno, $errstr, $errfile, $errline)
    {
        if (null !== $this->getLog()) {
            $priority = $this->_getErrorsPriority($errno);
            $message = $errstr . ' in ' . $errfile . ':' . $errline;
            $category = array('category' => $this->getLogCategory());
            $this->getLog()->log($message, $priority, $category);
        }
        $displayErrors = ini_get('display_errors');
        ini_set('display_errors', 'Off');
        if (ini_get('log_errors')) {
            $path = ini_get('error_log');
            if (is_writeable(dirname($path))) {
                $message = sprintf(
                    "PHP %s:  %s in %s on line %d",
                    $errno,
                    $errstr,
                    $errfile,
                    $errline
                );
                error_log($message, 0);
            }
        }
        ini_set('display_errors', $displayErrors);
    }

    
    public function registerAsErrorHandler()
    {
        $handler = array($this, 'handleError');
        $errorLevel = E_ALL | E_STRICT;
        $this->_oldErrorHandler = set_error_handler($handler, $errorLevel);
        $this->_isErrorHandler = true;
        return $this;
    }

    
    public function getOldErrorHandler()
    {
        return $this->_oldErrorHandler;
    }

    
    public function unregisterAsErrorHandler()
    {
        if ($this->_isErrorHandler) {
            set_error_handler($this->_oldErrorHandler);
            $this->_isErrorHandler = false;
        }
        return $this;
    }

    
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        
        if (error_reporting() == 0) {
            return;
        }
        $this->_logError($errno, $errstr, $errfile, $errline);
        throw new Robo47_ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}

