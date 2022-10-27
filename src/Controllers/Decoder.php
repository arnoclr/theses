<?php

namespace App\Controllers;

use App\Model\Searcher;

class Decoder
{
    private $pdo;
    private $stopwords = ['by', 'par', 'from', 'depuis', 'after', 'après', 'before', 'avant', 'entre', 'à', 'et', 'en', 'in'];
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

        if ($from > -1) {
            $searcher->after($from - 1);
        }

        if ($to > -1) {
            $searcher->before($to + 1);
        }

        if ($in > -1) {
            $searcher->in($in);
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

        return $searcher;
    }

    public function decode(): Searcher
    {
        return clone $this->searcher;
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
        $author = '';
        $appendable = false;
        $words = explode(' ', $this->q);
        foreach ($words as $i => $word) {
            if (in_array($word, $this->stopwords)) {
                $appendable = false;
            }
            if ($appendable) {
                $author .= " $word";
            }
            if (in_array($word, ['by', 'par'])) {
                $appendable = true;
            }
        }
        $this->filteredq = str_replace($author, '', $this->filteredq);
        $this->filteredq = preg_replace('/(par|by)(\W|$)/i', '', $this->filteredq);
        return substr($author, 1);
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
        return preg_replace('/\s+/', ' ', $this->filteredq . ' ' . $this->getAuthorString());
    }
}
