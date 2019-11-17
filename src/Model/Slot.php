<?php

namespace Bot\Model;

class Slot
{
    protected $type;
    protected $name;

    public function __construct()
    {
    }

    public static function createFromArray(string $name, array $data)
    {
        $obj = new self();
        $obj->name = $name;
        $obj->type = $data['type'] ?? null;
        $obj->required = $data['required'] ?? null;
        $obj->priority = $data['priority'] ?? null;
        return $obj;
    }

    public function getType()
    {
        return $this->type;
    }
    public function getName()
    {
        return $this->name;
    }
}
