<?php

$q = $_POST['q'];
$email = $_POST['email'];
$token = bin2hex(random_bytes(32));
$title = "Erreur";

require "src/utils/verifyCaptcha.php";

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
