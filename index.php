<?php

require_once 'vendor/autoload.php';
require_once 'src/utils.php';

use App\Controllers\Decoder;
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

        $decoder = new Decoder($pdo, $q);

        $regions = $decoder->decode()->groupByRegions()->orderBy('total', 'DESC')->get();
        $regionalArray = Charts::getRegionalArray($regions, true);
        $moreAccurate = $decoder->decode()->limit(8)->get();
        $years = $decoder->decode()->groupByYears()->get();

        $timelineData = Charts::getYearsList($years);
        $resultsNumber = array_reduce($timelineData, function ($a, $b) {
            return $a + $b;
        }, 0);

        // get specified author name
        $by = $decoder->getAuthorString();
        $queryWithoutAuthor = $decoder->getQueryWithoutAuthor();

        // search if people with this name exists
        $isPerson = $searcher->from('people')->searchByName($q)->exists();
        if ($isPerson) {
            $person = $searcher->from('people')->searchByName($q)->first();
        }

        // date range
        $dateString = $decoder->getDateRangeString();
        $queryWithoutDate = $decoder->getQueryWithoutDate();

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
