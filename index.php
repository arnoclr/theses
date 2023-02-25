<?php

require_once 'vendor/autoload.php';
require_once 'src/utils.php';

use App\Controllers\Decoder;
use App\Model\Database;
use App\Model\Searcher;
use App\Model\Charts;
use App\Model\These;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$action = $_GET['action'] ?? null;
$headless = $_GET['headless'] ?? false;
$pdo = Database::getPDO();
$searcher = new Searcher($pdo);
$q = $_GET['q'] ?? null;

if (!$headless)
    require "src/Views/header.php";

switch ($action) {
    case 'search':
        $startedAt = microtime(true);

        $comparisons = explode(',', $_GET['q']);

        $regions = [];
        $years = [];
        $subjectsCount = [];
        $regionalArray = [];
        $subjectsArray = [];
        $timelineData = [];
        $moreAccurate = [];

        $resultsNumberForComparison = floor(8 / count($comparisons));

        if (count($comparisons) > 4) {
            die('4 comparaisons maximum');
        }

        foreach ($comparisons as $pos => $q) {
            $decoder = new Decoder($pdo, $q);

            $regions[] = $decoder->decode()->groupByRegions()->orderBy('total', 'DESC')->get();
            $moreAccurate[] = $decoder->decodeAndOrder()->limit($resultsNumberForComparison)->get();
            $years[] = $decoder->decode()->groupByYears()->get();
            $subjectsCount[] = These::subjectsCount($decoder->decode()->get());

            $establishmentData = $searcher->getEstablishment($q);

            if (false) { // $at
                $moreAccurate[$pos] = $searcher->from('theses')->fromEstablishment($establishmentData)->limit(8)->get();
            }

            $regionalArray[] = Charts::getRegionalArray($regions[$pos], false);
            $subjectsArray[] = Charts::getSubjectsSeries($subjectsCount[$pos], false);
            $timelineData[] = Charts::getYearsList($years[$pos]);
        }

        $resultsNumber = 0;

        // dd($subjectsArray[0]);

        foreach ($timelineData as $timeline) {
            $resultsNumber += array_reduce($timeline, function ($a, $b) {
                return $a + $b;
            }, 0);
        }

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
        $onlineLink = These::getOnlineLink($thesis);
        require "src/Views/thesis.php";
        break;

    case 'person':
        break;

    case 'embed':
        $graph = $_GET['graph'] ?? 'timeline';
        $version = intval($_GET['v']) ?? 1;
        switch ($graph) {
            case 'value':
                # code...
                break;

            default:
                # code...
                break;
        }

    default:
        $searcher = new Searcher($pdo);
        $regionalArray = getOrCache('home.regions', 60, function () use ($searcher) {
            $regions = $searcher->groupByRegions()->orderBy('total', 'DESC')->get();
            return Charts::getRegionalArray($regions, true);
        });
        $timelineData = getOrCache('home.timeline', 60, function () use ($searcher) {
            $years = $searcher->groupByYears()->get();
            return Charts::getYearsList($years);
        });
        $thesesCount = array_reduce($timelineData, function ($a, $b) {
            return $a + $b;
        }, 0);
        $peopleCount = getOrCache('home.people', 60, function () use ($searcher) {
            return $searcher->from('people')->count();
        });
        $randomTitle = $searcher->randomOne()->get()[0]->title;
        require "src/Views/home.php";
        break;
}

if (!$headless)
    require "src/Views/footer.php";
