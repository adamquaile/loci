<?php

namespace spec\AdamQuaile\Loci\Exceptions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UnexpectedNumberOfResultsExceptionSpec extends ObjectBehavior
{
    function it_has_sensible_message()
    {
        $this->beConstructedWith(4, 3);
        $this->getMessage()->shouldReturn("Expected 4, got 3");
    }
}
