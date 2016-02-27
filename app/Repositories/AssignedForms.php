<?php
namespace Repositories;
class AssignedForms
{
    public $object;
    public function __construct($object = null)
    {
        $this->object = $object;
    }
}