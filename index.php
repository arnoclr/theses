<?php

$action = $_GET['action'] ?? null;

require "src/Views/header.php";

switch ($action) {
    case 'search':
        # code...
        break;

    default:
        require "src/Views/home.php";
        break;
}

require "src/Views/footer.php";
