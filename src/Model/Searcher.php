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
        $this->statement = "SELECT * FROM theses WHERE 1 = 1";
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
        $this->replaceStatement('/FROM theses/', "FROM $table");
        return $this;
    }

    // WHERE
    public function before(int $date): Searcher
    {
        $this->addCondition("date_year < $date");
        return $this;
    }

    public function after(int $date): Searcher
    {
        $this->addCondition("date_year > $date");
        return $this;
    }

    public function fromAuthor(string $name): Searcher
    {
        // TODO: join request
        return $this;
    }

    public function search(string $q): Searcher
    {
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

    public function byId(int $id): Searcher
    {
        $this->addCondition("iddoc = $id");
        return $this;
    }

    // ORDER BY
    public function orderBy($field, $order = 'ASC'): Searcher
    {
        $this->appendRule("ORDER BY $field $order");
        return $this;
    }

    // GROUP BY
    public function groupByRegions(): Searcher
    {
        // select region, count(*) from theses natural join establishments group by region;
        $this->replaceStatement('/(SELECT.+) FROM \w+/', '$1, count(*) as total FROM theses NATURAL JOIN establishments');
        $this->appendRule('GROUP BY region');
        return $this;
    }

    public function groupByYears(): Searcher
    {
        $this->replaceStatement('/(SELECT.+) FROM \w+/', 'SELECT count(*) as total, date_year FROM theses');
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
        $this->statement = "SELECT * FROM theses WHERE 1 = 1";
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

    // UTILS
    public function _debug(): string
    {
        return $this->statement;
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
}
