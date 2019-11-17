<?php

namespace Bot\Model;

class Response
{
    protected $text;
    protected $status;

    public function __construct(string $text, $status = 'OK')
    {
        $this->text = $text;
        $this->status = $status;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
