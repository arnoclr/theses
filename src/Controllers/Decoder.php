<?php

namespace App\Controllers;

use App\Model\Searcher;

class Decoder
{
    private $pdo;
    private $q;
    private $filteredq;
    private $searcher;
    private $filters;

    public function __construct($pdo, $q)
    {
        $this->pdo = $pdo;
        $this->q = $q;
        $this->filteredq = "";
        $this->searcher = $this->initSearcher();
    }

    // analyse human language query and return associated sql request
    private function initSearcher(): Searcher
    {
        $searcher = new Searcher($this->pdo);

        // request can be "formed like this" tri:recent :enligne par:"Jean Dupont" avant:2019

        // cut spaces and keep words between quotes
        $queryParts = preg_split('/\s+(?=(?:[^"]*"[^"]*")*[^"]*$)/', $this->q);
        $notExactMatchSentence = "";

        foreach ($queryParts as $queryPart) {
            if ($this->isFilter($queryPart)) {
                $this->addFilter($queryPart);
            } else {
                $this->filteredq .= $queryPart . " ";

                if ($this->isQuoted($queryPart)) {
                    $searcher->exactMatch($this->removeQuotes($queryPart));
                } else {
                    $notExactMatchSentence .= $queryPart . " ";
                }
            }
        }

        if ($this->getFilter('tri') === 'recent') {
            $searcher->orderBy('date_year', 'DESC');
        }
        if ($this->getFilter('tri') === 'ancien') {
            $searcher->orderBy('date_year', 'ASC');
        }
        if ($this->getFilter('enligne') === true) {
            $searcher->online();
        }
        if ($this->getFilter('par')) {
            $searcherAlt = new Searcher($this->pdo);
            $isPerson = $searcherAlt->from('people')->searchByName($this->getFilter('par'))->exists();
            if ($isPerson) {
                $person = $searcherAlt->from('people')->searchByName($this->getFilter('par'))->first();
                $searcher->authorIs($person->firstname, $person->lastname);
            }
        }
        if ($this->getFilter('avant')) {
            $searcher->before($this->getFilter('avant'));
        }
        if ($this->getFilter('apres')) {
            $searcher->after($this->getFilter('apres'));
        }
        if ($this->getFilter('en')) {
            $searcher->in($this->getFilter('en'));
        }
        if ($this->getFilter('a')) {
            $searcher->at($this->getFilter('a'));
        }

        $searcher->search($notExactMatchSentence);

        // dd($searcher->_debug());

        return $searcher;
    }

    public function decode(): Searcher
    {
        return clone $this->searcher;
    }

    public function getFilter(string $key)
    {
        return $this->filters[$key] ?? false;
    }

    private function isQuoted(string $str): bool
    {
        return strpos($str, '"') !== false;
    }

    private function removeQuotes(string $str): string
    {
        return str_replace('"', '', $str);
    }

    private function isFilter(string $queryPart): bool
    {
        return strpos($queryPart, ':') !== false;
    }

    private function addFilter(string $queryPart): void
    {
        $filter = explode(':', $queryPart);
        $key = $filter[0] === "" ? $filter[1] : $filter[0];
        $val = $filter[0] === "" ? true : $this->removeQuotes($filter[1]);
        $this->filters[$key] = $val;
    }

    private function isValidYear(int $year): bool
    {
        return $year >= 1985 && $year <= date('Y');
    }
}
