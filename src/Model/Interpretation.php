<?php

namespace Bot\Model;

class Interpretation
{
    protected $intent;
    protected $slots;

    public function __construct()
    {
    }

    public static function fromIntent(Intent $intent, array $slots)
    {
        $obj = new self();
        $obj->intent = $intent;
        $obj->slots = $slots;
        return $obj;
    }

    public function getIntent(): ?Intent
    {
        return $this->intent;
    }

    public function getSlots()
    {
        return $this->slots;
    }

}
