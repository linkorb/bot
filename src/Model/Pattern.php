<?php

namespace Bot\Model;

class Pattern
{
    protected $segments = [];

    public function __construct()
    {
    }

    public static function createFromArray(array $rows)
    {
        $obj = new self();
        foreach ($rows as $row)
        {
            $segment = PatternSegment::createFromArray($row);
            $obj->addSegment($segment);
        }
        return $obj;
    }


    public static function createFromString(string $input)
    {
        $input = preg_replace('/\s+/', ' ', $input); // remove repeated spaces
        $input = str_replace("\'", '"', $input); // normalize quotes

        $part = str_getcsv($input, " ", '"',  "\\");

        // trim punctuations
        foreach ($part as $k=>$v) {
            $part[$k] = trim($v, '!,.? ');
        }

        $rows = [];
        foreach ($part as $k=>$v) {
            if ((substr($v, 0, 1)=='{') && (substr($v, -1, 1)=='}')) {
                $v = substr($v, 1, -1);
                $row = [
                    'type' => 'slot',
                    'slot' => $v,
                ];
            } else {
                $row = [
                    'type' => 'static',
                    'value' => $v,
                ];
            }
            $rows[] = $row;
        }
        // print_r($rows);exit();

        return self::createFromArray($rows);
    }


    public function addSegment(PatternSegment $segment)
    {
        $this->segments[] = $segment;
    }

    public function getSegments()
    {
        return $this->segments;
    }

}
