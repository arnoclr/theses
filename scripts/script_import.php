<?php

require "../vendor/autoload.php";

use \JsonMachine\Items;
use \App\Model\Database;

$pdo = Database::getPDO();

// https://stackoverflow.com/a/37726178/11651419 save big files to server
file_put_contents("data.json.tmp", fopen("https://tfressin.fr/thesesviz/extract_theses.json", 'r'));

// this usually takes few kB of memory no matter the file size
$theses = Items::fromFile('data.json.tmp');

foreach ($theses as $these) {
    // just process $user as usual
    $pdo->exec("DELETE FROM theses WHERE iddoc = {$these->iddoc}");
    $pdo->prepare("INSERT INTO theses (iddoc, nnt, status, source) VALUES (:iddoc, :nnt, :status, :source)")->execute([
        'iddoc' => $these->iddoc,
        'nnt' => $these->nnt,
        'status' => $these->status,
        'source' => $these->source
    ]);
}

// delete temp file
unlink("data.json.tmp");
