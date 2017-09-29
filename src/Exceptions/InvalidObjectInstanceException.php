<?php

namespace e200\MakeAccessible\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidObjectIntanceException.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class InvalidObjectInstanceException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
