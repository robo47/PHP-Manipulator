<?php

namespace PHP\Manipulator\Exception;

class TokenException extends \Exception implements PHPManipulatorException
{
    const MISSING_TOKEN_TYPE                     = 1;
    const MISSING_TOKEN_VALUE                    = 2;
    const CREATE_ONLY_SUPPORTS_STRING_AND_ARRAY  = 3;
    const EXPECTED_TYPE_TO_BE_INT_OR_NULL        = 4;
    const EXPECTED_LINE_NUMBER_TO_BE_INT_OR_NULL = 5;
    const EXPECTED_TOKEN_VALUE_TO_BE_STRING      = 6;
    const EXPECTED_TOKEN_VALUE_TO_NOT_BE_EMPTY   = 7;
}
