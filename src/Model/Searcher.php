<?php

namespace App\Model;

class Searcher
{
    private $statement;
    private $pdo;
    private $params;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->statement = "SELECT * FROM `theses` WHERE 1 = 1";
        $this->params = [];
    }

    // SELECT
    public function select(array $params): Searcher
    {
        $selectStatement = join(', ', $params);
        $this->replaceStatement('/SELECT \*/', "SELECT $selectStatement");
        return $this;
    }

    // FROM
    public function from(string $table): Searcher
    {
        $this->replaceStatement('/FROM `theses`/', "FROM `$table`");
        return $this;
    }

    // JOIN
    private function naturalJoin(string $table): Searcher
    {
        $this->statement = str_replace('WHERE', "NATURAL JOIN `$table` WHERE", $this->statement);
        return $this;
    }

    private function leftJoin(string $table, string $on): Searcher
    {
        if (strpos($this->statement, "LEFT JOIN `$table`") !== false) {
            return $this;
        }
        $this->statement = str_replace('WHERE', "LEFT JOIN `$table` ON $on WHERE", $this->statement);
        return $this;
    }

    // WHERE
    public function where(string $col, string $op, string $val): Searcher
    {
        $randomBind = uniqid();
        $this->addCondition("`$col` $op :$randomBind");
        $this->addParam($randomBind, $val);
        return $this;
    }

    public function before(int $year): Searcher
    {
        $this->addCondition("date_year < $year");
        return $this;
    }

    public function after(int $year): Searcher
    {
        $this->addCondition("date_year > $year");
        return $this;
    }

    public function in(int $year): Searcher
    {
        $this->addCondition("date_year = $year");
        return $this;
    }

    public function search(string $q): Searcher
    {
        if ($q == "") {
            return $this;
        }
        // natural language search
        $this->addCondition("MATCH (title, summary, subjects, partners, establishments) AGAINST (:q IN NATURAL LANGUAGE MODE)");
        $this->addParam('q', $q);
        return $this;
    }

    public function exactMatch(string $terms): Searcher
    {
        $i = uniqid();
        $this->addCondition("(title LIKE :q$i OR summary LIKE :q$i OR subjects LIKE :q$i)");
        $this->addParam("q$i", "%$terms%");
        return $this;
    }

    public function searchByName(string $q): Searcher
    {
        $names = explode(' ', $q);
        foreach ($names as $i => $name) {
            $this->addCondition("(firstname LIKE :q$i OR lastname LIKE :q$i)");
            $this->addParam("q$i", "%$name%");
        }
        return $this;
    }

    public function authorIs(string $firstname, string $lastname): Searcher
    {
        $this->from('people');
        $this->naturalJoin('theses_people');
        $this->naturalJoin('theses');
        $this->addCondition("firstname = :firstname AND lastname = :lastname AND role = 'aut'");
        $this->addParam('firstname', $firstname);
        $this->addParam('lastname', $lastname);
        return $this;
    }

    public function byId(int $id): Searcher
    {
        $this->addCondition("iddoc = $id");
        return $this;
    }

    public function online(): Searcher
    {
        $this->addCondition("online = 1");
        return $this;
    }

    public function at(string $establishmentName): Searcher
    {
        $this->leftJoin('establishments', 'establishments.identifiant_idref = theses.etab_id_ref');
        $this->addCondition("nom_court = :establishmentName");
        $this->addParam('establishmentName', $establishmentName);
        return $this;
    }

    public function near(float $lat, float $lon, int $radiusKm = 25): Searcher
    {
        $this->leftJoin('establishments', 'establishments.identifiant_idref = theses.etab_id_ref');
        $LAT = "SUBSTRING_INDEX(Géolocalisation, ',', 1)";
        $LON = "SUBSTRING_INDEX(Géolocalisation, ',', -1)";
        // $this->select(["*", "$LAT AS lat", "$LON AS lon"]);
        $this->addCondition("SQRT(POW(69.1 * ($LAT - :lat), 2) + POW(69.1 * (:lon - $LON) * COS($LAT / 57.3), 2)) < $radiusKm");
        $this->addParam('lat', $lat);
        $this->addParam('lon', $lon);
        return $this;
    }

    public function nearCity(string $city, int $radiusKm = 25): Searcher
    {
        $url = "https://nominatim.openstreetmap.org/search?format=json&email=webmaster.theses@arno.cl&q=" . urlencode($city);
        $content = getOrCache($url, 60 * 24 * 7, function () use ($url) {
            return @file_get_contents($url);
        });
        if ($content != false) {
            $json = json_decode($content, true);
            if (empty($json)) {
                throw new \Exception("No results for $city");
            } else {
                $best_match = $json[0];
                $lat = $best_match['lat'];
                $lon = $best_match['lon'];
                $this->near($lat, $lon, $radiusKm);
            }
        }
        return $this;
    }

    // ORDER BY
    private function isOrdered(): bool
    {
        return preg_match('/ORDER BY/', $this->statement);
    }

    public function orderBy($field, $order = 'ASC'): Searcher
    {
        if ($this->isOrdered()) {
            $this->replaceStatement('/ORDER BY \S+ \S+/', "ORDER BY $field $order");
            return $this;
        }
        $this->appendRule("ORDER BY $field $order");
        return $this;
    }

    public function removeOrderBy(): Searcher
    {
        $this->replaceStatement('/ORDER BY \S+ \S+/', "");
        return $this;
    }

    public function randomOne(): Searcher
    {
        $this->appendRule("ORDER BY RAND()");
        $this->limit(1);
        return $this;
    }

    // GROUP BY
    public function groupBy(string $field): Searcher
    {
        $this->appendRule("GROUP BY `$field`");
        return $this;
    }

    public function groupByRegions(): Searcher
    {
        // select region, count(*) from theses natural join establishments group by region;
        $this->leftJoin('establishments', 'establishments.identifiant_idref = theses.etab_id_ref');
        $this->select(['`Code région`', 'count(*) as total']);
        $this->groupBy('Code région');
        $this->removeOrderBy();
        return $this;
    }

    public function groupByYears(): Searcher
    {
        $this->select(['count(*) as total', 'date_year']);
        $this->groupBy('date_year');
        $this->removeOrderBy();
        return $this;
    }

    // LIMIT
    public function limit(int $limit): Searcher
    {
        $this->appendRule("LIMIT $limit");
        return $this;
    }

    // GET
    public function get(): array
    {
        $statement = $this->pdo->prepare($this->statement);
        $statement->execute($this->params);
        $this->statement = "SELECT * FROM `theses` WHERE 1 = 1";
        $this->params = [];
        return $statement->fetchAll();
    }

    public function first(): ?object
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    public function exists(): bool
    {
        return count($this->limit(1)->get()) > 0;
    }

    public function count(): int
    {
        return count($this->get());
    }

    // UTILS
    public function _debug(): array
    {
        return [
            'statement' => $this->statement,
            'params' => $this->params,
        ];
    }

    private function replaceStatement(string $regex, string $replacement): void
    {
        $this->statement = preg_replace($regex, $replacement, $this->statement);
    }

    private function appendRule(string $rule): void
    {
        $this->statement .= " $rule";
    }

    private function addCondition(string $condition): void
    {
        $this->statement = preg_replace('/WHERE 1 = 1/', "WHERE 1 = 1 AND $condition", $this->statement);
    }

    private function addParam(string $key, string $value): void
    {
        $this->params[$key] = $value;
    }

    private function getEstablishmentCode(string $establishment)
    {
        $statement = $this->pdo->prepare("SELECT code_etab FROM `establishments` WHERE name = :name");
        $statement->execute(['name' => $establishment]);
        return $statement->fetch()->code_etab ?? "";
    }

    public function getEstablishment(string $q): ?object
    {
        $this->from('establishments');
        $this->addCondition("Libellé LIKE :q OR nom_court LIKE :q");
        $this->addParam('q', "%$q%");
        return $this->first();
    }

    public function fromEstablishment(object $establishmentData): Searcher
    {
        $this->from('theses');
        $this->addCondition("etab_id_ref = :identifiant_idref");
        $this->addParam('identifiant_idref', $establishmentData->identifiant_idref);
        return $this;
    }
}
