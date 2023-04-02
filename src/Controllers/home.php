<?php

use App\Model\Charts;
use App\Model\Searcher;

$searcher = new Searcher($pdo);
$timelineData = [];
$timelineData[] = getOrCache('home.timeline', 60, function () use ($searcher) {
    $years = $searcher->groupByYears()->get();
    return Charts::getYearsList($years);
});
$thesesCount = array_reduce($timelineData[0], function ($a, $b) {
    return $a + $b;
}, 0);
$peopleCount = getOrCache('home.people', 60, function () use ($searcher) {
    return $searcher->from('people')->count();
});
$etabsCount = getOrCache('home.etabs', 60, function () use ($searcher) {
    return $searcher->from('establishments')->count();
});
$randomTitle = $searcher->randomOne()->get()[0]->title;
require "src/Views/home.php";
