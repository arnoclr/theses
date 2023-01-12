<?php

namespace App\Model;

const DB_SEPARATOR = ';';
// public displayed
const BING_MAP_KEY = "AiSO_FZNso9JJnrkixZ6T3d142q2DnTBLhQDVuZXeGFAI_gcnTD11M7JwvhevmzA";

class These
{
    // add here static methods for a thesis

    public static function getSubjects(object $thesis): array
    {
        return explode(DB_SEPARATOR, $thesis->subjects);
    }

    public static function getEstablishments(object $thesis): array
    {
        return explode(DB_SEPARATOR, $thesis->establishments);
    }

    public static function getMap(object $thesis): string
    {
        $establishment = urlencode(self::getEstablishments($thesis)[0]);
        return "https://dev.virtualearth.net/REST/V1/Imagery/Map/Road/{$establishment}?mapSize=420,230&key=" . BING_MAP_KEY;
    }

    public static function getOnlineLink(object $thesis): string
    {
        return "https://theses.fr/{$thesis->nnt}/document";
    }

    public static function flag(object $thesis): string
    {
        $dict = [
            "en" => "us",
        ];
        $lang = $thesis->lang;
        if (isset($dict[$lang])) {
            $lang = $dict[$lang];
        }
        return "https://flagcdn.com/$lang.svg";
    }

    public static function subjectsCount(array $theses, int $length = 8): array
    {
        $subjects = [];
        foreach ($theses as $thesis) {
            foreach (self::getSubjects($thesis) as $subject) {
                if (isset($subjects[$subject]) && !in_array($subject, ["", "..."])) {
                    $subjects[$subject]++;
                } else {
                    $subjects[$subject] = 1;
                }
            }
        }
        arsort($subjects);
        return array_slice($subjects, 0, $length);
    }
}
