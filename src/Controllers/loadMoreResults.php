<?php

use App\Controllers\Decoder;
use App\Model\Charts;
use App\Model\These;

$page = $_GET['p'] ?? 1;
$comparisons = explode(',', $_GET['q']);
$limit = floor(8 / count($comparisons));

$offset = ($page - 1) * $limit;

if (count($comparisons) > 4) {
    die('4 comparaisons maximum');
}

foreach ($comparisons as $pos => $q) {
    $decoder = new Decoder($pdo, trim($q));

    $theses = $decoder->decode()->limit($limit)->offset($offset)->get();
    $subjectsCount[] = These::subjectsCount($theses);

    $color = count($comparisons) > 1 ? Charts::getColorAt($pos) . "15" : "transparent";

    foreach ($theses as $these) {
        require "src/Views/includes/coloredListItem.php";
        require "src/Views/includes/searchResult.php";
        require "src/Views/includes/imagesCarousel.php";
        echo "</li>";
    }
}
