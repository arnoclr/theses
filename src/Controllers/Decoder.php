<?php

namespace App\Controllers;

use App\Model\Searcher;

class Decoder
{
    private $pdo;
    private $q;
    private $filteredq;
    private $notExactMatchSentence;
    private $searcher;
    private $filters;
    private $peoples;
    private $autoLocalized = false;

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
        $this->notExactMatchSentence = "";

        foreach ($queryParts as $queryPart) {
            if ($this->isFilter($queryPart)) {
                $this->addFilter($queryPart);
            } else {
                $this->filteredq .= $queryPart . " ";

                if ($this->isQuoted($queryPart)) {
                    $searcher->exactMatch($this->removeQuotes($queryPart));
                } else {
                    $this->notExactMatchSentence .= $queryPart . " ";
                }
            }
        }

        $bounds = $this->getRequestBoundaries();

        if ($bounds !== null) {
            $this->autoLocalized = true;
            $searcher->inBoundaries($bounds[0], $bounds[1], $bounds[2], $bounds[3]);
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
        if ($this->getFilter('vers')) {
            $searcher->nearCity($this->getFilter('vers'));
        }
        if ($this->getFilter('lat') && $this->getFilter('lon')) {
            $lat = $this->getFilter('lat');
            $lon = $this->getFilter('lon');
            $radiusKm = $this->getRadiusKm();
            $searcher->near($lat, $lon, $radiusKm);
        }

        $this->peoples = (new Searcher($this->pdo))->getPeopleList($this->filteredq);

        if (count($this->peoples) === 1) {
            $searcher->authorIs($this->peoples[0]->firstname, $this->peoples[0]->lastname);
            return $searcher;
        }

        $searcher->search($this->notExactMatchSentence);

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

    private function getRequestBoundaries(): ?array
    {
        $url = "https://nominatim.openstreetmap.org/search?format=json&email=webmaster.theses@arno.cl&q=" . urlencode($this->notExactMatchSentence);
        $content = getOrCache($url, 60 * 24 * 7, function () use ($url) {
            return @file_get_contents($url);
        });
        if ($content != false) {
            $json = json_decode($content, true);
            if (empty($json)) {
                return null;
            } else {
                $best_match = $json[0];
                if ($best_match['importance'] > 0.5) {
                    $bounds = $best_match['boundingbox'];
                    return [floatval($bounds[0]), floatval($bounds[2]), floatval($bounds[1]), floatval($bounds[3])];
                }
            }
        }
        return null;
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

    private function getRadiusKm(): int
    {
        return $this->getFilter('rayon') !== false ? $this->getFilter('rayon') : 80;
    }

    public function displayableQuery(): string
    {
        return htmlspecialchars($this->filteredq);
    }

    public function queryContainExactMatchExpression(): bool
    {
        return preg_match('/"[\s\S]+"/', $this->filteredq);
    }

    public function isLocalizedQuery(): bool
    {
        return $this->autoLocalized === true || $this->getFilter('lat') !== false;
    }

    public function isAutolocalizedQuery(): bool
    {
        return $this->autoLocalized === true;
    }

    public function authorName(): ?string
    {
        if (count($this->peoples) === 1) {
            return $this->peoples[0]->firstname . ' ' . $this->peoples[0]->lastname;
        }
        return null;
    }

    public function displayableFilters(): array
    {
        $filters = [];
        if ($this->getFilter('tri')) {
            switch ($this->getFilter('tri')) {
                case 'recent':
                    $filters[] = "Triés par plus récents d'abord";
                    break;
                case 'ancien':
                    $filters[] = "Triés par plus anciens d'abord";
                    break;
            }
        }
        if ($this->getFilter('enligne')) {
            $filters[] = "Uniquement les thèses disponibles en ligne";
        }
        if ($this->getFilter('par')) {
            $filters[] = "Par " . $this->getFilter('par');
        }
        if ($this->getFilter('avant')) {
            $filters[] = "Avant " . $this->getFilter('avant');
        }
        if ($this->getFilter('apres')) {
            $filters[] = "Après " . $this->getFilter('apres');
        }
        if ($this->getFilter('en')) {
            $filters[] = "En " . $this->getFilter('en');
        }
        if ($this->getFilter('a')) {
            $filters[] = "À " . $this->getFilter('a');
        }
        if ($this->getFilter('vers')) {
            $filters[] = "Vers " . $this->getFilter('vers');
        }
        if ($this->autoLocalized === false && $this->getFilter('lat') && $this->getFilter('lon')) {
            $lat = $this->getFilter('lat');
            $lon = $this->getFilter('lon');
            $radiusKm = $this->getRadiusKm();
            $filter = "À $radiusKm km autour de $lat, $lon";

            $url = "https://nominatim.openstreetmap.org/reverse?lat={$lat}&lon={$lon}&format=json&email=webmaster.theses@arno.cl";
            $content = getOrCache($url, 60 * 24 * 7, function () use ($url) {
                return @file_get_contents($url);
            });
            if ($content != false) {
                $json = json_decode($content, true);
                if (empty($json)) {
                    throw new \Exception("No results for $lat, $lon");
                } else {
                    $filter = "à {$radiusKm} km autour de " . $json['address']['municipality'] . ", " . $json['address']['state'];
                }
            }

            $filters[] = $filter;
        }

        return $filters;
    }
}
