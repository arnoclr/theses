<?php

require_once 'vendor/autoload.php';
require_once 'src/utils.php';

use App\Model\Database;
use App\Model\Searcher;
use App\Model\Charts;

$action = $_GET['action'] ?? null;
$pdo = Database::getPDO();
$searcher = new Searcher($pdo);

require "src/Views/header.php";

switch ($action) {
    case 'search':
        # code...
        break;

    default:
        $regions = $searcher->select(['region'])->groupByRegions()->orderBy('total', 'DESC')->get();
        $regionalArray = Charts::getRegionalArray($regions, true);
        require "src/Views/home.php";
        break;
}

require "src/Views/footer.php";
