<?php

require_once 'vendor/autoload.php';
require_once 'src/utils.php';

use App\Controllers\Decoder;
use App\Model\Database;
use App\Model\Searcher;
use App\Model\Charts;
use App\Model\These;

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

switch ($action) {
    case 'search':
        $startedAt = microtime(true);

        $comparisons = explode(',', $_GET['q']);

        $regions = [];
        $years = [];
        $subjectsCount = [];
        $regionalArray = [];
        $subjectsArray = [];
        $timelineData = [];
        $moreAccurate = [];
        $decoders = [];

        $resultsNumberForComparison = floor(8 / count($comparisons));

        if (count($comparisons) > 4) {
            die('4 comparaisons maximum');
        }

        foreach ($comparisons as $pos => $q) {
            $decoder = new Decoder($pdo, trim($q));

            $regions[] = $decoder->decode()->groupByRegions()->get();
            $moreAccurate[] = $decoder->decode()->limit($resultsNumberForComparison)->get();
            $years[] = $decoder->decode()->groupByYears()->get();
            $subjectsCount[] = These::subjectsCount($decoder->decode()->get());

            if (false) { // $at
                $moreAccurate[$pos] = $searcher->from('theses')->fromEstablishment($establishmentData)->limit(8)->get();
            }

            $regionalArray[] = Charts::getRegionalArray($regions[$pos], false);
            $subjectsArray[] = Charts::getSubjectsSeries($subjectsCount[$pos], false);
            $timelineData[] = Charts::getYearsList($years[$pos]);

            $decoders[] = $decoder;
        }

        $establishmentData = null;
        if (count($comparisons) === 1 && $decoder->getFilter('a') === false) {
            $establishmentData = $searcher->getEstablishment($q);
        }

        $resultsNumber = 0;

        // dd($subjectsArray[0]);

        foreach ($timelineData as $timeline) {
            $resultsNumber += array_reduce($timeline, function ($a, $b) {
                return $a + $b;
            }, 0);
        }

        $endAt = microtime(true);
        $time = $endAt - $startedAt;

        require "src/Views/results.php";
        break;

    case 'view':
        $id = intval($_GET['tid']);
        $thesis = $searcher->byId($id)->first();
        $subjects = These::getSubjects($thesis);
        $establishments = These::getEstablishments($thesis);
        $map = These::getMap($thesis);
        $flag = These::flag($thesis);
        $onlineLink = These::getOnlineLink($thesis);
        require "src/Views/thesis.php";
        break;

    case 'submitAlert':
        $q = $_POST['q'];
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(32));
        $title = "Erreur";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hint = "L'adresse email n'est pas valide.";
            require "src/Views/alert.php";
            require "src/Views/footer.php";
            exit;
        }

        $req = $pdo->prepare('SELECT * FROM alerts WHERE email = :email AND q = :q');
        $req->execute([
            'email' => $email,
            'q' => $q,
        ]);
        $alertAlreadyExists = $req->rowCount() !== 0;

        if ($alertAlreadyExists) {
            $hint = "Vous avez déjà une alerte pour cette recherche.";
            require "src/Views/alert.php";
            require "src/Views/footer.php";
            exit;
        }

        $_SESSION['alertToken_' . $email] = [
            "_token" => $token,
            "q" => $q,
        ];

        $host = $_SERVER['HTTP_HOST'];

        $send = sendEmail($email, "Confirmez votre alerte pour {$q}", <<<HTML
        <p>Vous avez demandé la création d'une alerte pour {$q}. Finalisez l'activation en cliquant sur le lien.</p>
        <a href="http://{$host}/?action=confirmAlert&v=1&email={$email}&token={$token}">ACTIVER L'ALERTE</a>
        <br>
        <br>
        <small style="color: #777;">Vous n'êtes pas à l'origine de cette demande ? Ignorez cet email.</small>
        HTML);

        if ($send->status !== 202) {
            $title = "Erreur";
            $hint = "L'envoi de l'email à échoué. Veuillez réessayer.";
        } else {
            $title = "Dernière étape";
            $hint = "Un email de confirmation vous a été envoyé. Cliquez sur le lien pour activer l'alerte.";
        }

        require "src/Views/alert.php";
        break;

    case 'confirmAlert':
        $email = $_GET['email'];
        $token = $_GET['token'];
        $sessionData = $_SESSION['alertToken_' . $email] ?? false;
        $title = "Erreur";

        if ($sessionData === false) {
            $hint = "Essayez d'ouvrir le lien dans le même navigateur que celui utilisé pour créer l'alerte. Si cela ne fonctionne pas, alors le mail de confirmation a expiré.";
            require "src/Views/alert.php";
            require "src/Views/footer.php";
            exit;
        }

        if ($sessionData['_token'] !== $token) {
            $hint = "Le lien de confirmation est invalide.";
            require "src/Views/alert.php";
            require "src/Views/footer.php";
            exit;
        }

        $q = $sessionData['q'];

        $pdo->prepare('INSERT INTO alerts (email, q, unsubscribe_token, created_at) VALUES (:email, :q, :token, NOW())')
            ->execute([
                'email' => $email,
                'q' => $q,
                'token' => bin2hex(random_bytes(32)),
            ]);

        unset($_SESSION['alertToken_' . $email]);

        $title = "Alerte créée";
        $hint = "Vous recevrez un email chaque fois qu'une nouvelle thèse correspondant à votre recherche sera publiée.";
        require "src/Views/alert.php";

        break;

    case 'person':
        break;

    case 'embed':
        $graph = $_GET['graph'] ?? 'timeline';
        $version = intval($_GET['v']) ?? 1;
        switch ($graph) {
            case 'value':
                # code...
                break;

            default:
                # code...
                break;
        }

    default:
        $searcher = new Searcher($pdo);
        $regionalArray = getOrCache('home.regions', 60, function () use ($searcher) {
            $regions = $searcher->groupByRegions()->orderBy('total', 'DESC')->get();
            return Charts::getRegionalArray($regions, true);
        });
        $timelineData = getOrCache('home.timeline', 60, function () use ($searcher) {
            $years = $searcher->groupByYears()->get();
            return Charts::getYearsList($years);
        });
        $thesesCount = array_reduce($timelineData, function ($a, $b) {
            return $a + $b;
        }, 0);
        $peopleCount = getOrCache('home.people', 60, function () use ($searcher) {
            return $searcher->from('people')->count();
        });
        $randomTitle = $searcher->randomOne()->get()[0]->title;
        require "src/Views/home.php";
        break;
}

if (!$headless)
    require "src/Views/footer.php";
