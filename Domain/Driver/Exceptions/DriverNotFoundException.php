<?php

namespace Domain\Driver\Exceptions;

use Exception;

class DriverNotFoundException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct("Driver with ID '{$id}' not found.", 404);
    }
}
