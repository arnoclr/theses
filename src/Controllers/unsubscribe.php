<?php

$version = intval($_GET['v'] ?? 1);
$token = $_GET['token'];
$redirect = $_GET['redirect'] ?? false;

switch ($version) {
    case 1:
        if ($redirect == false) {
            $pdo->prepare('DELETE FROM alerts WHERE unsubscribe_token = :token')
                ->execute([
                    'token' => $token,
                ]);
            die('Vous avez bien été désabonné.');
        }
        echo "<script>window.location.href = '/?action=unsubscribe&v=1&token={$token}';</script>";
        break;

    default:
        break;
}
