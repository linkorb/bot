<?php

namespace Bot\Example;

use Bot\Model\Bot;
use Bot\Model\Response;

class ExampleUtil
{
    public static function ping(Bot $bot, array $variables)
    {
        $target = $variables['target'] ?? null;

        $output = shell_exec("ping -c1 " . escapeshellarg($target));
        // var_dump($output);
        if ($output) {
            return new Response($output);
        }

        return new Response('ping failed', 'ERROR');
    }

    public static function setName(Bot $bot, array $variables)
    {
        $name = $variables['name'] ?? null;
        $bot->setVariable('username', $name);

        return new Response('Hi, ' . $name . '!', 'OK');
    }
}
