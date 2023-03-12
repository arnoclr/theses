<?php

use App\Model\Charts;
use App\Model\Searcher;

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
