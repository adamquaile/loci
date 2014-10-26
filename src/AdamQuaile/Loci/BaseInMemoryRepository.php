<?php

namespace AdamQuaile\Loci;

class BaseInMemoryRepository
{
    protected $objects;

    public function __construct()
    {
        $this->objects = new ObjectRepository();
    }

}