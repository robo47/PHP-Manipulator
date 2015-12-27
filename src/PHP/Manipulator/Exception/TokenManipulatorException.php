<?php

namespace PHP\Manipulator\Exception;

use Exception;

class TokenManipulatorException extends Exception implements PHPManipulatorException
{
    const TOKEN_IS_NO_MULTILINE_COMMENT = 1;
}
