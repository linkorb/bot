<?php

namespace Bot\Fulfillment;

use Bot\Model\Bot;
use Bot\Model\Response;

class EchoFulfillment implements FulfillmentInterface
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function fulfill(Bot $bot, array $variables): Response
    {
        $text = $this->config['text'] ?? null;
        foreach ($variables as $k=>$v) {
            $text = str_replace('{' . $k . '}', $v, $text);
        }

        return new Response($text);
    }
}
