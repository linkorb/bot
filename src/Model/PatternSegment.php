<?php

namespace Bot\Model;

class PatternSegment
{
    protected $type;
    protected $slotName;
    protected $value;

    public function __construct()
    {
    }

    public static function createFromArray(array $data)
    {
        $obj = new self();
        $obj->type = $data['type'] ?? null;
        $obj->slotName = $data['slot'] ?? null;
        $obj->value = $data['value'] ?? null;
        return $obj;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSlotName()
    {
        return $this->slotName;
    }

    public function getValue()
    {
        return $this->value;
    }

}
