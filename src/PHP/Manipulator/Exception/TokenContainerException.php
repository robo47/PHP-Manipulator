<?php

namespace PHP\Manipulator\Exception;

use Exception;

class TokenContainerException extends Exception implements PHPManipulatorException
{
    const CONTAINER_ONLY_SUPPORTS_TOKENS     = 1;
    const OFFSET_DOES_NOT_EXIST              = 2;
    const TOKEN_DOES_NOT_EXIST_IN_CONTAINER  = 3;
    const EXPECTED_OFFSET_TO_BE_INT          = 4;
    const EXPECTED_TOKEN_TO_BE_OF_TYPE_TOKEN = 5;
}
