<?php

namespace PHP\Manipulator\Exception;

use Exception;

class TokenFinderException extends Exception implements PHPManipulatorException
{
    const UNSUPPORTED_START_TOKEN = 1;
}
