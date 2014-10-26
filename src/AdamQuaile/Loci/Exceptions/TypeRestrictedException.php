<?php

namespace AdamQuaile\Loci\Exceptions;

use AdamQuaile\Loci\ObjectRepository;
use Exception;

class TypeRestrictedException extends \InvalidArgumentException
{
    public function __construct(ObjectRepository $repository, $offendingObject, $expectedType)
    {
        if (!is_object($offendingObject)) {
            throw new \LogicException('Second argument to TypeRestrictedException should be object');
        }

        parent::__construct(sprintf(
            "%s only accepts objects of type %s. %s given",
            get_class($repository),
            $expectedType,
            get_class($offendingObject)
        ));
    }

}
