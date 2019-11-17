<?php

namespace Bot\Model;

class Intent
{
    protected $name;
    protected $callback;
    protected $patterns = [];
    protected $slots = [];
    protected $confirmationText;
    protected $fulfillment;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromArray(array $config)
    {
        $obj = new self($config['name'] ?? null);
        $obj->confirmationText = $config['confirmation']['text'] ?? null;

        foreach (($config['slots'] ?? []) as $slotName => $slotConfig) {
            $slot = Slot::createFromArray($slotName, $slotConfig);
            $obj->registerSlot($slot);
        }

        foreach (($config['patterns'] ?? []) as $patternConfig) {
            if (is_array($patternConfig)) {
                $pattern = Pattern::createFromArray($patternConfig);
                $obj->registerPattern($pattern);
            }
            if (is_string($patternConfig)) {
                $pattern = Pattern::createFromString($patternConfig);
                $obj->registerPattern($pattern);
            }
        }

        $fulfillmentType = $config['fulfillment']['type'] ?? null;
        if ($fulfillmentType) {
            $className = 'Bot\\Fulfillment\\' . ucfirst($fulfillmentType) . 'Fulfillment';
            $obj->fulfillment = new $className($config['fulfillment']);
        }
        return $obj;
    }

    public function setConfirmationText(string $text)
    {
        $this->confirmationText = $text;
    }

    public function getConfirmationText(array $slots)
    {
        $text = $this->confirmationText;
        foreach ($slots as $k=>$v) {
            $text = str_replace('{' . $k . '}', $v, $text);
        }
        return $text;
    }


    public function getName()
    {
        return $this->name;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function registerPattern(Pattern $pattern)
    {
        $this->patterns[] = $pattern;
    }

    public function getPatterns()
    {
        return $this->patterns;
    }

    public function registerSlot(Slot $slot)
    {
        $this->slots[$slot->getName()] = $slot;
    }

    public function getSlots()
    {
        return $this->slots;
    }

    public function getFulfillment()
    {
        return $this->fulfillment;
    }
}
