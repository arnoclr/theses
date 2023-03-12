<?php

use App\Model\These;

$id = intval($_GET['tid']);
$thesis = $searcher->byId($id)->first();
$subjects = These::getSubjects($thesis);
$establishments = These::getEstablishments($thesis);
$map = These::getMap($thesis);
$flag = These::flag($thesis);
$onlineLink = These::getOnlineLink($thesis);
require "src/Views/thesis.php";
