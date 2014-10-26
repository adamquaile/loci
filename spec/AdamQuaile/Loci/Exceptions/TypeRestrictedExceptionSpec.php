<?php

namespace spec\AdamQuaile\Loci\Exceptions;

use AdamQuaile\Loci\ObjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TypeRestrictedExceptionSpec extends ObjectBehavior
{
    function it_is_initializable(ObjectRepository $repo)
    {
        $offendingObject = (object) ['a' => 1];
        $offendingType = get_class($offendingObject);

        $expectedType = 'DateTime';

        $repo = $repo->getWrappedObject();

        $this->beConstructedWith($repo, $offendingObject, $expectedType);
        $this->getMessage()->shouldReturn(sprintf(
            "%s only accepts objects of type %s. %s given",
            get_class($repo),
            $expectedType,
            $offendingType
        ));
    }
}
