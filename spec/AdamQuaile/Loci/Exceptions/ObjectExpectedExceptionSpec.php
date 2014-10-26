<?php

namespace spec\AdamQuaile\Loci\Exceptions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectExpectedExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AdamQuaile\Loci\Exceptions\ObjectExpectedException');
    }
}
