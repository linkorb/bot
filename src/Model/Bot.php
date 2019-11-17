<?php

namespace Bot\Model;

use Symfony\Component\Yaml\Yaml;

class Bot
{
    protected $variables = [];
    protected $intents = [];

    public function registerIntent(Intent $intent)
    {
        $this->intents[$intent->getName()] = $intent;
    }

    public function setVariable(string $k, string $v)
    {
        $this->variables[$k] = $v;
    }

    public function getVariable($k)
    {
        return $this->variables[$k] ?? null;
    }

    public function getIntents()
    {
        return $this->intents;
    }

    public function handle(string $input): Response
    {
        $interpretation = $this->interpret($input);
        if ($interpretation) {
            $intent = $interpretation->getIntent();
            $fulfillment = $intent->getFulfillment();
            if (!$fulfillment) {
                return new Response("Understood, but I don't know how to fulfillment this request", 'ERROR');
            }
            $variables = $interpretation->getSlots();
            foreach ($this->variables as $k=>$v) {
                $variables['bot.' . $k] = $v;
            }
            return $fulfillment->fulfill($this, $variables);
        }
        return new Response('I do not understand', 'ERROR');
    }

    public function interpret(string $input): ?Interpretation
    {
        $input = preg_replace('/\s+/', ' ', $input); // remove repeated spaces
        $input = str_replace("\'", '"', $input); // normalize quotes

        $part = str_getcsv($input, " ", '"',  "\\");

        // trim punctuations
        foreach ($part as $k=>$v) {
            $part[$k] = trim($v, '!,.? ');
        }

        foreach ($this->intents as $intent) {
            foreach ($intent->getPatterns() as $pattern) {
                $match = true;
                $slots = [];
                $p = 0;
                foreach ($pattern->getSegments() as $segment) {
                    if (count($part)<=$p) {
                        $match = false;
                    } else {
                        $value = ($part[$p] ?? null);
                        if ($segment->getType()=='static') {
                            if (is_string($segment->getValue())) {
                                if (strtolower($value) != strtolower($segment->getValue())) {
                                    $match = false;
                                }
                            }
                            if (is_array($segment->getValue())) {
                                if (!in_array(strtolower($value), $segment->getValue())) {
                                    $match = false;
                                }
                            }
                        }
                        if ($segment->getType()=='slot') {
                            $slots[$segment->getSlotName()] = $value;
                        }
                    }
                    $p++;
                }
                if ($match) {
                    $interpretation = Interpretation::fromIntent($intent, $slots);
                    return $interpretation;
                }
            }
        }
        return null;

    }

    public function match(string $pattern, string $input)
    {
        return false;
    }

    public function loadConfigPath(string $path): self
    {
        $filenames = glob($path . '/*.intent.yaml');
        foreach ($filenames as $filename) {
            $yaml = file_get_contents($filename);
            $config = Yaml::parse($yaml);
            $intent = Intent::fromArray($config);
            $this->registerIntent($intent);
        }
        return $this;
    }

    public function loadEnvironmentVariables($prefix = 'BOT_')
    {
        $keys = getenv();
        foreach ($keys as $k=>$v) {
            if (substr($k, 0, strlen($prefix))==$prefix) {
                $k = substr($k, strlen($prefix));
                $k = strtolower($k);
                // echo $k . '=' . $v . PHP_EOL;
                $this->setVariable($k, $v);
            }
        }
    }
}
