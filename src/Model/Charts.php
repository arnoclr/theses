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

    public static function getYearsList($obj, $inJson = false)
    {
        $currentYear = date('Y');
        $startYear = 1985;
        // fill all year with 0
        $array = array_fill(0, $currentYear - $startYear, 0);
        foreach ($obj as $item) {
            $index = $item->date_year - $startYear;
            if ($index > 0)
                $array[$index] = intval($item->total);
        }
        return $inJson ? json_encode($array) : $array;
    }
}
