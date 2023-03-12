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

    public static function getCommonSubjects(object $these, array $subjectCount, int $max = 4): array
    {
        $selfSubjects = self::getSubjects($these);
        $subjects = [];
        foreach ($selfSubjects as $subject) {
            if (isset($subjectCount[$subject]) && $subjectCount[$subject] > 1) {
                $subjects[] = $subject;
            }
        }
        return array_slice($subjects, 0, $max);
    }

    public static function isCloseMatch(object $these, string $query): bool
    {
        $wordsNumber = explode(" ", $query);
        if (count($wordsNumber) < 2) {
            return false;
        }
        $query = strtolower($query);
        $summary = strtolower($these->summary);
        return strpos($summary, $query) !== false;
    }

    public static function containExactMatch(string $haystack, string $needle): bool
    {
        $haystack = mb_strtolower($haystack, "UTF-8");
        $needle = mb_strtolower($needle, "UTF-8");
        // dd([$haystack, $needle]);
        return strpos($haystack, $needle) !== false;
    }

    public static function highlightSummaryWith(string $summary, string $query, int $maxLength = 220): string
    {
        $query = mb_strtolower($query, "UTF-8");
        $summary = mb_strtolower($summary, "UTF-8");
        $pos = strpos($summary, $query);
        if ($pos === false) {
            return $summary;
        }
        $start = max(0, $pos - $maxLength);
        $end = min(strlen($summary), $pos + $maxLength);
        $summary = mb_substr($summary, $start, $end - $start, "UTF-8");
        $summary = str_replace($query, "<strong>$query</strong>", $summary);
        return $summary;
    }

    public static function getEstabShortName(object $thesis): string
    {
        $pdo = Database::getPDO();
        $searcher = new Searcher($pdo);
        $estab = $searcher->from('establishments')
            ->where('identifiant_idref', '=', $thesis->etab_id_ref)
            ->first();
        if ($estab === NULL) {
            return "";
        }
        return $estab->{'Libellé'} ?? $estab->nom_court;
    }
}
