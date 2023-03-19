<?php

require_once 'vendor/autoload.php';
require_once 'src/utils.php';

use App\Model\Database;
use App\Model\Searcher;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$action = $_GET['action'] ?? null;
$headless = $_GET['headless'] ?? false;
$pdo = Database::getPDO();
$searcher = new Searcher($pdo);
$q = $_GET['q'] ?? null;

session_start();

if (!$headless)
    require "src/Views/header.php";

if (file_exists("src/Controllers/$action.php"))
    try {
        require "src/Controllers/$action.php";
    } catch (Exception $e) {
        require "src/Views/errors/500.php";
        if ($_SERVER['HOST'] === 'localhost')
            dd($e);
    }
elseif ($action === null)
    require "src/Controllers/home.php";
else
    require "src/Views/errors/404.php";

if (!$headless)
    require "src/Views/footer.php";
