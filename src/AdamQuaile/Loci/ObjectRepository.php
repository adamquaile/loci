<?php

namespace AdamQuaile\Loci;

use AdamQuaile\Loci\Exceptions\ObjectExpectedException;
use AdamQuaile\Loci\Exceptions\TypeRestrictedException;
use AdamQuaile\Loci\Exceptions\UnexpectedNumberOfResultsException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ObjectRepository
{
    private $objects = [];
    private $typeRestriction;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function add($object)
    {
        if (!is_object($object) || $object instanceof \Closure) {
            throw new ObjectExpectedException($object);
        }
        if ($this->typeRestriction && !($object instanceof $this->typeRestriction)) {
            throw new TypeRestrictedException($this, $object, $this->typeRestriction);
        }
        $this->objects[$this->hash($object)] = $object;
    }

    public function findAll()
    {
        return array_values($this->objects);
    }

    public function findByCallback(callable $callback)
    {
        return array_values(array_filter($this->objects, $callback));
    }

    public function restrictTo($type)
    {
        $this->typeRestriction = $type;
    }

    public function contains($object)
    {
        return array_key_exists($this->hash($object), $this->objects);
    }

    private function hash($object)
    {
        return spl_object_hash($object);
    }

    public function remove($object)
    {
        unset($this->objects[$this->hash($object)]);
    }

    public function findBy($criteria)
    {
        return $this->findMultipleUsingFilter(
            $this->combineFilters($this->parseFilters($criteria))
        );
    }

    public function findOneBy($criteria)
    {
        $objects = $this->findBy($criteria);
        if (count($objects) !== 1) {
            throw new UnexpectedNumberOfResultsException(1, count($objects));
        }

        return $objects[0];
    }

    private function findMultipleUsingFilter(callable $filter)
    {
        return $this->findByCallback($filter);
    }

    private function combineFilters($filters)
    {
        return function($object) use ($filters) {

            foreach ($filters as $filter) {
                if (!$filter($object)) {
                    return false;
                }
            }

            return true;
        };
    }

    private function parseFilters($criteria)
    {
        $filters = [];
        foreach ($criteria as $path => $expected) {
            $filters[] = function($object) use ($path, $expected) {
                return $this->accessor->getValue($object, $path) == $expected;
            };
        };
        return $filters;
    }


}
