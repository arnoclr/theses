<?php

// verify if script is called from command line
if (php_sapi_name() != "cli") {
    die("This script cannot be run outside of CLI mode.");
}

require "../vendor/autoload.php";
require "../src/utils.php";
require "../src/utils/alerts.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

use \App\Model\Database;

$pdo = Database::getPDO();

$alerts = $pdo->query("SELECT * FROM alerts")->fetchAll();

foreach ($alerts as $alert) {
    sendAlert($alert);
}
