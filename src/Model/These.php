<?php

namespace App\Model;

const DB_SEPARATOR = ';';
// public displayed
const BING_MAP_KEY = "AiSO_FZNso9JJnrkixZ6T3d142q2DnTBLhQDVuZXeGFAI_gcnTD11M7JwvhevmzA";

class These
{
    // add here static methods for a thesis

    public static function getSubjects($thesis)
    {
        return explode(DB_SEPARATOR, $thesis->subjects);
    }

    public static function getEstablishments($thesis)
    {
        return explode(DB_SEPARATOR, $thesis->establishments);
    }

    public static function getMap($thesis)
    {
        $establishment = urlencode(self::getEstablishments($thesis)[0]);
        return "https://dev.virtualearth.net/REST/V1/Imagery/Map/Road/{$establishment}?mapSize=420,230&key=" . BING_MAP_KEY;
    }
}
