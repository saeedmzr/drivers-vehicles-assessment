<?php

namespace Domain\Vehicle\Exceptions;

use Exception;

class VehicleNotFoundException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct("Vehicle with ID '{$id}' not found.", 404);
    }
}
