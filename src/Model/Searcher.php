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

    // WHERE
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
        // get all terms between quotes
        // exact match search
        preg_match_all('/"([^"]+)"/', $q, $results);
        foreach ($results[1] as $i => $term) {
            $this->addCondition("(title LIKE :q$i OR summary LIKE :q$i OR subjects LIKE :q$i)");
            $this->addParam("q$i", "%$term%");
        }
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

    public function at(string $establishmentNameOrCode): Searcher
    {
        // if name is 4 letters UPPEr
        $code_etab = $establishmentNameOrCode;
        if (!preg_match('/^[A-Z0-9]{4}$/', $establishmentNameOrCode)) {
            $code_etab = $this->getEstablishmentCode($establishmentNameOrCode);
        }
        $this->addCondition("code_etab = :code_etab");
        $this->addParam('code_etab', $code_etab);
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

    // GROUP BY
    public function groupByRegions(): Searcher
    {
        // select region, count(*) from theses natural join establishments group by region;
        $this->naturalJoin('establishments');
        $this->select(['region', 'count(*) as total']);
        $this->appendRule('GROUP BY region');
        return $this;
    }

    public function groupByYears(): Searcher
    {
        $this->select(['count(*) as total', 'date_year']);
        $this->appendRule('GROUP BY date_year');
        $this->orderBy('date_year', 'ASC');
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

    public function first(): object
    {
        return $this->limit(1)->get()[0];
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
}
