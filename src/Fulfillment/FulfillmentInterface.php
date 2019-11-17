<?php

namespace Bot\Fulfillment;

use Bot\Model\Bot;
use Bot\Model\Response;

interface FulfillmentInterface
{
    public function fulfill(Bot $bot, array $variables): Response;
}
