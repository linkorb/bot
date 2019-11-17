<?php

namespace Bot\Fulfillment;

use Bot\Model\Bot;
use Bot\Model\Response;

class CallableFulfillment implements FulfillmentInterface
{
    protected $config;


    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function fulfill(Bot $bot, array $variables): Response
    {
        $c = $this->config['callable'];
        return $c($bot, $variables);
    }
}
