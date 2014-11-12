<?php

namespace spec\AdamQuaile\Loci;

use Acme\Widget;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

final class ObjectRepositorySpec extends ObjectBehavior
{

    function it_only_supports_objects()
    {
        $exception = 'AdamQuaile\Loci\Exceptions\ObjectExpectedException';
        $this->shouldThrow($exception)->duringAdd(4);
        $this->shouldThrow($exception)->duringAdd('1');
        $this->shouldThrow($exception)->duringAdd(array());
        $this->shouldThrow($exception)->duringAdd(function() {});
    }

    function it_keeps_record_of_all_objects(\stdClass $obj1, \stdClass $obj2)
    {
        $this->add($obj1);
        $this->findAll()->shouldBeLike([$obj1]);

        $this->add($obj2);
        $this->findAll()->shouldBeLike([$obj1, $obj2]);
    }

    function it_reports_whether_object_is_stored(\stdClass $obj1)
    {
        $this->contains($obj1)->shouldReturn(false);
        $this->add($obj1);
        $this->contains($obj1)->shouldReturn(true);
    }

    function it_supports_removing_objects(\stdClass $obj1)
    {
        $this->add($obj1);
        $this->remove($obj1);
        $this->contains($obj1)->shouldReturn(false);
    }

    function it_allows_searching_by_complex_fields()
    {
        $obj1 = (object) ['a' => (object) ['b' => 2]];
        $this->add($obj1);
        $this->findBy(['a.b' => 2])->shouldBeLike([$obj1]);
    }

    function it_allows_searching_by_callback()
    {
        $odd    = (object) ['a' => 1];
        $even   = (object) ['a' => 2];

        $this->add($odd);
        $this->add($even);

        $filterFn = function ($obj) {
            return $obj->a % 2 == 0;
        };
        $this->findByCallback($filterFn)->shouldReturn([$even]);

        $this->findOneByCallback($filterFn)->shouldReturn($even);
    }

    function it_can_be_restricted_to_a_type()
    {
        $this->restrictTo('DateTime');
        $this->add(new \DateTime());

        $this
            ->shouldThrow('AdamQuaile\Loci\Exceptions\TypeRestrictedException')
            ->duringAdd(new \DateTimeZone('UTC'));
    }

    function it_throws_exception_when_unexpected_number_of_results_returned()
    {
        $this->add((object) []);
        $this
            ->shouldThrow('AdamQuaile\Loci\Exceptions\UnexpectedNumberOfResultsException')
            ->duringFindOneByCallback(function() { return false; });
    }

}
