<?php

namespace AdamQuaile\Loci\Exceptions;

use Exception;

class ObjectExpectedException extends \DomainException
{
    public function __construct($actualValue = null)
    {
        parent::__construct('Expected object, got ' . gettype($actualValue));
    }

}
