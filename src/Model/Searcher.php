<?php

namespace App\Model;

class Searcher
{
    private $statement;
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->statement = "SELECT * FROM theses WHERE 1 = 1 LIMIT 10";
        $this->params = [];
    }

    // SELECT
    public function select(array $params): Searcher
    {
        $selectStatement = join(', ', $params);
        $this->replaceStatement('/SELECT \*/', "SELECT $selectStatement");
        return $this;
    }

    // WHERE
    public function before(int $date): Searcher
    {
        $this->addCondition("date < $date");
        return $this;
    }

    public function after(int $date): Searcher
    {
        $this->addCondition("date > $date");
        return $this;
    }

    public function fromAuthor(string $name): Searcher
    {
        $this->addCondition("(author_firstname LIKE %:name% OR author_lastname LIKE %:name%)");
        $this->params[] = ['name' => $name];
        return $this;
    }

    // ORDER BY
    public function orderBy($field, $order = 'ASC'): Searcher
    {
        $this->replaceStatement('/(LIMIT \d+)/', "ORDER BY $field $order $1");
        return $this;
    }

    // GROUP BY
    public function groupByRegions(): Searcher
    {
        // select region, count(*) from theses natural join establishments group by region;
        $this->replaceStatement('/(SELECT.+) FROM \w+/', '$1, count(*) as total FROM theses NATURAL JOIN establishments');
        $this->replaceStatement('/(LIMIT \d+)/', 'GROUP BY region $1');
        return $this;
    }

    // LIMIT
    public function limit(int $limit): Searcher
    {
        $this->replaceStatement('/LIMIT \d+/', "LIMIT $limit");
        return $this;
    }

    // GET
    public function get(): array
    {
        $statement = $this->pdo->prepare($this->statement);
        $statement->execute($this->params);
        return $statement->fetchAll();
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

    private function addCondition(string $condition): void
    {
        $this->statement = preg_replace('/WHERE 1 = 1/', "WHERE 1 = 1 AND $condition", $this->statement);
    }
}
