<?php

namespace Bot\Model;

class Request
{
    protected $username;
    protected $sentence;

    public function setUsername(string $username)
    {
        $this->username = $username;
    }
    public function setSentence(string $sentence)
    {
        $this->sentence = $sentence;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function getSentence()
    {
        return $this->sentence;
    }
}
