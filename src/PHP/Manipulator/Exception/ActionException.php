<?php

namespace PHP\Manipulator\Exception;

use Exception;

class ActionException extends Exception implements PHPManipulatorException
{
    const NO_OPTION_BY_NAME = 1;
    const NO_COLON_FOUND    = 2;
}
