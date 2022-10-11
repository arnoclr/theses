<?php

require_once 'vendor/autoload.php';
require_once 'src/utils.php';

use App\Model\Database;
use App\Model\Searcher;
use App\Model\Charts;

$action = $_GET['action'] ?? null;
$pdo = Database::getPDO();
$searcher = new Searcher($pdo);
$q = $_GET['q'] ?? null;

require "src/Views/header.php";

switch ($action) {
    case 'search':
        $startedAt = microtime(true);
        $regions = $searcher->select(['region'])->search($q)->groupByRegions()->orderBy('total', 'DESC')->get();
        $regionalArray = Charts::getRegionalArray($regions, true);
        $moreAccurate = $searcher->search($q)->limit(10)->get();
        $years = $searcher->search($q)->groupByYears()->get();
        $timelineData = Charts::getYearsList($years);
        $endAt = microtime(true);
        $time = $endAt - $startedAt;
        $resultsNumber = array_reduce($timelineData, function ($a, $b) {
            return $a + $b;
        }, 0);
        require "src/Views/results.php";
        break;

    default:
        require "src/Views/home.php";
        break;
}

require "src/Views/footer.php";
