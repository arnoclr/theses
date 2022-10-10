<?php

// verify if script is called from command line
if (php_sapi_name() != "cli") {
    die("This script cannot be run outside of CLI mode.");
}

const DB_SEPARATOR = ";";

require "../vendor/autoload.php";

use \JsonMachine\Items;
use \App\Model\Database;

$pdo = Database::getPDO();

// reset tables
$pdo->exec("DROP TABLE `theses_people`");
$pdo->exec("DROP TABLE `people`");
$pdo->exec("DROP TABLE `theses`");
$pdo->exec("DROP TABLE `establishments`");

$sqlgen = file_get_contents("init.sql");
$pdo->exec($sqlgen);

$sqlEtab = file_get_contents("establishments.sql");
$pdo->exec($sqlEtab);

// https://stackoverflow.com/a/37726178/11651419 save big files to server
file_put_contents("data.json.tmp", fopen("https://tfressin.fr/thesesviz/extract_theses.json", 'r'));

// this usually takes few kB of memory no matter the file size
$theses = Items::fromFile('data.json.tmp');

// save hash of firstname and lastname linked to their auto increment id
$processed_people = [];

foreach ($theses as $these) {
    $lang = substr($these->langue, 0, 2);

    // insert these
    $pdo->prepare("INSERT INTO theses (iddoc, nnt, status, online, source, discipline, president_jury, lang, date_year, code_etab, title, summary, subjects, partners, oai_set_specs, embargo, establishments, wip) VALUES (:iddoc, :nnt, :status, :online, :source, :discipline, :president_jury, :lang, :date_year, :code_etab, :title, :summary, :subjects, :partners, :oai_set_specs, :embargo, :establishments, :wip)")->execute([
        'iddoc' => $these->iddoc,
        'nnt' => $these->nnt,
        'status' => $these->status,
        'online' => $these->accessible == "oui" ? 1 : 0,
        'source' => $these->source,
        'discipline' => getBetterLangageFor($these->discipline, $lang),
        'president_jury' => 0,
        'lang' => $lang,
        'date_year' => substr($these->nnt, 0, 4),
        'code_etab' => $these->code_etab,
        'title' => truncateForVarchar(getBetterLangageFor($these->titres, $lang)) ?? "Non renseigné",
        'summary' => getBetterLangageFor($these->resumes, $lang),
        'subjects' => truncateForVarchar(implodeWithSeparator(getBetterLangageFor($these->sujets ?? null, $lang))),
        'partners' => truncateForVarchar(implodeWithSeparator(array_map(function ($partner) {
            return $partner->nom;
        }, $these->partenaires ?? []))),
        'oai_set_specs' => implodeWithSeparator($these->oai_set_specs ?? []),
        'embargo' => $these->embargo ? date('Y-m-d', strtotime($these->embargo)) : null,
        'establishments' => truncateForVarchar(implodeWithSeparator(array_map(function ($etablissement) {
            return $etablissement->nom;
        }, $these->etablissements_soutenance ?? []))),
        'wip' => $these->these_sur_travaux == "oui" ? 1 : 0,
    ]);

    $people = [
        "dir" => $these->directeurs_these ?? [],
        "aut" => $these->auteurs ?? [],
        "jur" => $these->membres_jury ?? [],
        "rep" => $these->rapporteurs ?? []
    ];

    // link current these to people and create link in database
    foreach ($people as $role => $list) {
        foreach ($list as $person) {
            $firstname = $person->prenom ?? null;
            $lastname = $person->nom ?? null;
            $idref = $person->idref ?? null;
            $uuid = sha1(strtolower($firstname . $lastname));
            // check if people already exists in database
            if (!array_key_exists($uuid, $processed_people)) {
                $pdo->prepare("INSERT INTO people (idref, firstname, lastname) VALUES (:idref, :firstname, :lastname)")->execute([
                    'idref' => $idref,
                    'firstname' => $firstname ?? "",
                    'lastname' => $lastname ?? ""
                ]);
                $processed_people[$uuid] = $pdo->lastInsertId();
            }
            // insert these id and people id with his role
            $pdo->prepare("INSERT INTO theses_people (iddoc, id, role) VALUES (:iddoc, :id, :role)")->execute([
                'iddoc' => $these->iddoc,
                'id' => $processed_people[$uuid],
                'role' => $role
            ]);
        }
    }

    // edit jury president id
    if (!empty($these->president_jury)) {
        $pdo->prepare("UPDATE theses SET president_jury = :id WHERE iddoc = :iddoc")->execute([
            'id' => $processed_people[sha1(strtolower($these->president_jury->prenom . $these->president_jury->nom))] ?? 0,
            'iddoc' => $these->iddoc
        ]);
    }
}

// return langage based field in french if possible, english otherwise, or in default langage if not available
function getBetterLangageFor($entry, $default, $truncate = true)
{
    $lang_order = ['fr', 'en', $default];
    foreach ($lang_order as $lang) {
        if (isset($entry->{$lang})) {
            return $entry->{$lang};
        }
    }
    return null;
}

// transform an array to a string with defined const separator
function implodeWithSeparator($array)
{
    return $array == null ? null : implode(DB_SEPARATOR, $array);
}

// truncate to 255 chars to avoid mysql error
function truncateForVarchar($string, $length = 255)
{
    // use mb_str functions to avoid cutting a character in half
    return strlen($string) > $length ? mb_substr($string, 0, $length - 3, 'utf-8') . "..." : $string;
}

// delete temp file
unlink("data.json.tmp");

echo "Done !";
