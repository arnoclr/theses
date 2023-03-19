<?php

namespace App\Model;

class Charts
{
    public static function seriesColors(): array
    {
        return ["#2196f3", "#e53935", "#4caf50", "#fdd835"];
    }

    public static function highchartsSeriesColors(): string
    {
        return json_encode(self::seriesColors());
    }

    public static function getColorAt(int $pos): string
    {
        return self::seriesColors()[$pos];
    }

    private static function regionalCodeToISO(string $code)
    {
        $map = [
            "11" => "FR-IDF",
            "24" => "FR-CVL",
            "27" => "FR-BFC",
            "28" => "FR-NOR",
            "32" => "FR-HDF",
            "44" => "FR-GES",
            "52" => "FR-PDL",
            "53" => "FR-BRE",
            "75" => "FR-NAQ",
            "76" => "FR-OCC",
            "84" => "FR-ARA",
            "93" => "FR-PAC",
            "94" => "FR-20R",
            "01" => "FR-GP",
            "02" => "FR-MQ",
            "03" => "FR-GF",
            "04" => "FR-RE",
            "06" => "FR-YT",
        ];
        return $map[substr($code, 1, 2)] ?? "FR-NAN";
    }

    /**
     * Convert a PDO list of objects that contain region code and value to an array of array (region, value)
     * Can optionnaly parse the results to JSON to use with charts lib in JS
     */
    public static function getRegionalArray($obj, $inJson = false)
    {
        $array = [];
        foreach ($obj as $item) {
            if ($item->{"Code région"} === NULL) {
                continue;
            }
            $array[] = [strtolower(self::regionalCodeToISO($item->{"Code région"})), intval($item->total)];
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

    public static function getSubjectsSeries($obj, $inJson = false)
    {
        $array = [];
        foreach ($obj as $subject => $count) {
            $array[] = [
                'name' => $subject,
                'value' => intval($count),
                'colorValue' => intval($count)
            ];
        }
        return $inJson ? json_encode($array) : $array;
    }
}
