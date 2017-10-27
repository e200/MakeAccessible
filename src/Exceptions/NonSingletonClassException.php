<?php

namespace e200\MakeAccessible\Exceptions;

use Exception;
use Throwable;

/**
 * Class NonSingletonClassException.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class NonSingletonClassException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
