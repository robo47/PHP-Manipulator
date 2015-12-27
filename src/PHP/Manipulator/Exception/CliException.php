<?php

namespace PHP\Manipulator\Exception;

use Exception;

class CliException extends Exception implements PHPManipulatorException
{
    const UNABLE_TO_LOAD_CONFIG = 1;
}
