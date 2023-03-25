<?php

use App\Controllers\Decoder;
use App\Model\Charts;
use App\Model\These;

$startedAt = microtime(true);

$comparisons = explode(',', $_GET['q']);

$regions = [];
$years = [];
$subjectsCount = [];
$regionalArray = [];
$subjectsArray = [];
$timelineData = [];
$moreAccurate = [];
$decoders = [];

$resultsNumberForComparison = floor(8 / count($comparisons));

if (count($comparisons) > 4) {
    die('4 comparaisons maximum');
}

foreach ($comparisons as $pos => $q) {
    $decoder = new Decoder($pdo, trim($q));

    $moreAccurate[] = $decoder->decode()->limit($resultsNumberForComparison)->get();

    try {
        $regions[] = $decoder->decode()->groupByRegions()->get();
    } catch (\Exception $e) {
        $regions[] = [];
    }

    try {
        $years[] = $decoder->decode()->groupByYears()->get();
    } catch (\Exception $e) {
        $years[] = [];
    }

    try {
        $subjectsCount[] = These::subjectsCount($decoder->decode()->get());
    } catch (\Exception $e) {
        $subjectsCount[] = [];
    }

    if (false) { // $at
        $moreAccurate[$pos] = $searcher->from('theses')->fromEstablishment($establishmentData)->limit(8)->get();
    }

    $regionalArray[] = Charts::getRegionalArray($regions[$pos], false);
    $subjectsArray[] = Charts::getSubjectsSeries($subjectsCount[$pos], false);
    $timelineData[] = Charts::getYearsList($years[$pos]);

    $decoders[] = $decoder;
}

$establishmentData = null;
if (count($comparisons) === 1 && $decoder->getFilter('a') === false) {
    $establishmentData = $searcher->getEstablishment($q);
}

$resultsNumber = 0;

// dd($subjectsArray[0]);

foreach ($timelineData as $timeline) {
    $resultsNumber += array_reduce($timeline, function ($a, $b) {
        return $a + $b;
    }, 0);
}

$showMoreResults = $resultsNumber > 8;

$endAt = microtime(true);
$time = $endAt - $startedAt;

require "src/Views/results.php";
