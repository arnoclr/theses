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
        $haystack = trim($haystack);
        $haystack = mb_strtolower($haystack, "UTF-8");
        $needle = mb_strtolower($needle, "UTF-8");
        return strpos($haystack, $needle) !== false;
    }

    public static function highlightSummaryWith(string $summary, string $query, int $maxLength = 220): string
    {
        $query = trim($query);
        $query = str_replace('"', "", $query);
        $sentences = explode(".", $summary);
        $sentence = self::getBestMatchingSentence($sentences, $query);
        return self::highlightWords($sentence, $query);
    }

    public static function highlightWords(string $text, string $query): string
    {
        $words = explode(" ", $query);
        $words = array_filter($words, function ($word) {
            return strlen($word) > 2;
        });
        $wordsGroup = implode("|", $words);
        $regex = "/(({$wordsGroup})[ |\w{1,3}]*({$wordsGroup})|\b({$wordsGroup})\b)/iu";
        $text = preg_replace($regex, "<mark>$1</mark>", $text);
        return $text;
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

    private static function getWordsCountInSentence(array $words, string $sentence): int
    {
        $count = 0;
        foreach ($words as $word) {
            if (self::containExactMatch($sentence, $word)) {
                $count++;
            }
        }
        return $count;
    }

    private static function getBestMatchingSentence(array $sentences, string $query): string
    {
        $query = trim($query);
        $words = explode(" ", $query);
        $bestSentence = "";
        $bestCount = 0;
        foreach ($sentences as $sentence) {
            $count = self::getWordsCountInSentence($words, $sentence);
            if ($count > $bestCount) {
                $bestSentence = $sentence;
                $bestCount = $count;
            }
        }
        return $bestSentence;
    }
}
