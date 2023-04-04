<?php

require "src/utils/alerts.php";

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

$id = $pdo->lastInsertId();

$inserted = $pdo->query('SELECT * FROM alerts WHERE id = ' . $id)->fetch();

unset($_SESSION['alertToken_' . $email]);

sendAlert($inserted);

$title = "Alerte créée";
$hint = "Vous recevrez un email chaque fois qu'une nouvelle thèse correspondant à votre recherche sera publiée.";
require "src/Views/alert.php";
