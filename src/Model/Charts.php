<?php

namespace App\Model;

class Charts
{

    /**
     * Convert a PDO list of objects that contain region code and value to an array of array (region, value)
     * Can optionnaly parse the results to JSON to use with charts lib in JS
     */
    public static function getRegionalArray($obj, $inJson = false)
    {
        $array = [];
        foreach ($obj as $item) {
            $array[] = [strtolower($item->region), intval($item->total)];
        }
        return $inJson ? json_encode($array) : $array;
    }
}
