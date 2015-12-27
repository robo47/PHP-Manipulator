<?php

namespace PHP\Manipulator\Exception;

use Exception;

class ConfigException extends Exception implements PHPManipulatorException
{
    const FILE_NOT_FOUND      = 1;
    const OPTION_NOT_FOUND    = 2;
    const UNABLE_TO_OPEN_PATH = 3;
    const UNABLE_TO_READ_FILE = 4;
    const UNKNOWN_CAST_OPTION = 5;
    const XML_PARSE_ERROR     = 6;
}
