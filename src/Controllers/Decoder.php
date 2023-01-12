<?php

namespace App\Controllers;

use App\Model\Searcher;

class Decoder
{
    private $pdo;
    private $stopwords = ['by', 'par', 'from', 'depuis', 'after', 'après', 'before', 'avant', 'entre', 'à', 'et', 'en', 'in', 'les plus récentes', 'les plus anciennes', 'latest', 'oldest'];
    private $q;
    private $filteredq;
    private $searcher;

    public function __construct($pdo, $q)
    {
        $this->pdo = $pdo;
        $this->q = $q;
        $this->filteredq = $q;
        $this->searcher = $this->initSearcher();
    }

    // analyse human language query and return associated sql request
    private function initSearcher(): Searcher
    {
        $searcher = new Searcher($this->pdo);

        $from = $this->from();
        $to = $this->to();
        $in = $this->in();
        $author = $this->getAuthor();
        $at = $this->at();

        if ($from > -1) {
            $searcher->after($from - 1);
        }

        if ($to > -1) {
            $searcher->before($to + 1);
        }

        if ($in > -1) {
            $searcher->in($in);
        }
        if ($at) {
            $searcher->at($at);
        }

        if ($author) {
            $searcherAlt = new Searcher($this->pdo);
            $isPerson = $searcherAlt->from('people')->searchByName($author)->exists();
            if ($isPerson) {
                $person = $searcherAlt->from('people')->searchByName($author)->first();
                $searcher->authorIs($person->firstname, $person->lastname);
            }
        }

        $searcher->search($this->filteredq);

        // dd($searcher->_debug());

        return $searcher;
    }

    public function decode(): Searcher
    {
        return clone $this->searcher;
    }

    public function decodeAndOrder(): Searcher
    {
        $searcher = clone $this->searcher;

        $order = $this->getOrder();
        if ($order === 1) {
            $searcher->orderBy('date_year', 'DESC');
        } else if ($order === -1) {
            $searcher->orderBy('date_year', 'ASC');
        }

        return $searcher;
    }

    private function from(): int
    {
        return $this->extractDateAfterKeywords(['from', 'depuis', 'after', 'après', 'entre']);
    }

    private function to(): int
    {
        return $this->extractDateAfterKeywords(['to', 'à', 'before', 'avant', 'et']);
    }

    private function in(): int
    {
        return $this->extractDateAfterKeywords(['in', 'en']);
    }

    private function at(): string
    {
        return $this->extractStringAfterKeywords(['at', 'à']);
    }

    private function getOrder(): int
    {
        if (preg_match('/les plus récentes|latest/i', $this->q)) {
            $this->filteredq = str_replace(['les plus récentes', 'latest'], '', $this->filteredq);
            return 1;
        }
        if (preg_match('/les plus anciennes|oldest/i', $this->q)) {
            $this->filteredq = str_replace(['les plus anciennes', 'oldest'], '', $this->filteredq);
            return -1;
        }
        return 0;
    }

    private function extractDateAfterKeywords(array $keywords): int
    {
        $year = 0;
        $words = explode(' ', $this->q);
        foreach ($words as $i => $word) {
            if (in_array($word, $keywords)) {
                $year = intval($words[$i + 1]);
                if ($this->isValidYear($year)) {
                    // remove terms from query
                    $this->filteredq = str_replace("$word $year", '', $this->filteredq);
                    break;
                }
            }
        }
        return $this->isValidYear($year) ? $year : -1;
    }

    private function isValidYear(int $year): bool
    {
        return $year >= 1985 && $year <= date('Y');
    }

    private function getAuthor(): string
    {
        return $this->extractStringAfterKeywords(['by', 'par']);
    }

    private function extractStringAfterKeywords(array $stopwords): string
    {
        $res = '';
        $appendable = false;
        $words = explode(' ', $this->q);
        foreach ($words as $i => $word) {
            if (in_array($word, $this->stopwords)) {
                $appendable = false;
            }
            if ($appendable) {
                $res .= " $word";
            }
            if (in_array($word, $stopwords)) {
                $appendable = true;
            }
        }
        $stopwordsString = implode('|', $stopwords);
        $regex = "/({$stopwordsString})(\W|$)/i";
        $this->filteredq = str_replace($res, '', $this->filteredq);
        $this->filteredq = preg_replace($regex, '', $this->filteredq);
        return substr($res, 1);
    }

    // Front end data
    // return a string displayed on interface
    public function getDateRangeString(): string
    {
        $from = $this->from();
        $to = $this->to();
        $in = $this->in();
        $dateRangeString = '';
        if ($from > 0) {
            $dateRangeString .= "depuis $from";
        }
        if ($to > 0) {
            $dateRangeString .= " jusqu'à $to";
        }
        if ($in > 0) {
            $dateRangeString = "en $in";
        }
        return $dateRangeString;
    }

    public function getAuthorString(): string
    {
        $author = $this->getAuthor();
        return $author ? "par $author" : '';
    }

    public function getEstablishmentString(): string
    {
        $at = $this->at();
        return $at ? "à $at" : '';
    }

    public function getFilteredQuery(): string
    {
        return $this->filteredq;
    }

    public function getQueryWithoutAuthor(): string
    {
        $author = $this->getAuthor();
        $out = $author ? str_replace('par ' . $author, '', $this->q) : $this->q;
        return preg_replace('/\s+/', ' ', $out);
    }

    public function getQueryWithoutDate(): string
    {
        return preg_replace('/\s+/', ' ', $this->filteredq . ' ' . $this->getAuthorString() . ' ' . $this->getEstablishmentString());
    }

    public function getQueryWithoutEstablishment(): string
    {
        $at = $this->at();
        $out = $at ? str_replace('à ' . $at, '', $this->q) : $this->q;
        return preg_replace('/\s+/', ' ', $out);
    }
}
