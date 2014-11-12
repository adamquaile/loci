<?php

namespace AdamQuaile\Loci\Exceptions;

use Exception;

class UnexpectedNumberOfResultsException extends \RuntimeException
{
    public function __construct($expected, $actual)
    {
        parent::__construct("Expected $expected, got $actual");
    }

}
