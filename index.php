<?php

require_once 'vendor/autoload.php';
require_once 'src/utils.php';

use App\Model\Database;
use App\Model\Searcher;
use App\Model\Charts;
use App\Model\These;

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
        $moreAccurate = $searcher->search($q)->limit(8)->get();
        $years = $searcher->search($q)->groupByYears()->get();
        $timelineData = Charts::getYearsList($years);
        $resultsNumber = array_reduce($timelineData, function ($a, $b) {
            return $a + $b;
        }, 0);

        // search if people with this name exists
        $isPerson = $searcher->from('people')->searchByName($q)->exists();
        if ($isPerson)
            $person = $searcher->from('people')->searchByName($q)->first();

        $endAt = microtime(true);
        $time = $endAt - $startedAt;

        require "src/Views/results.php";
        break;

    case 'view':
        $id = intval($_GET['tid']);
        $thesis = $searcher->byId($id)->first();
        $subjects = These::getSubjects($thesis);
        $establishments = These::getEstablishments($thesis);
        $map = These::getMap($thesis);
        $flag = These::flag($thesis);
        require "src/Views/thesis.php";
        break;

    case 'person':
        break;

    default:
        require "src/Views/home.php";
        break;
}

require "src/Views/footer.php";
