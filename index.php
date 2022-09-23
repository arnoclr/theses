<?php

$action = $_GET['action'] ?? null;

switch ($action) {
    case 'search':
        # code...
        break;

    default:
        require "src/Views/index.php";
        break;
}
