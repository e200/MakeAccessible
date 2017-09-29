<?php

namespace e200\MakeAccessible\Exceptions;

use Exception;
use Throwable;

/**
 * Class PropertyNotFoundNotFoundException.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class PropertyNotFoundNotFoundException extends Exception implements ContainerExceptionInterface
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
