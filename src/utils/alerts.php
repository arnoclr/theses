<?php

use App\Controllers\Decoder;
use App\Model\Searcher;

function sendAlert(object $alert): bool
{
    global $pdo;
    $host = $_ENV['HOST'];
    $decoder = new Decoder($pdo, trim($alert->q) . " tri:recent");
    $theses = $decoder->decode()->limit(8)->get();

    $ul = '<ul style="list-style: none; margin: 0; padding: 0;">';
    $thesesCount = "8+";
    foreach ($theses as $i => $these) {
        if ($these->iddoc === $alert->last_these_id) {
            $thesesCount = $i;
            break;
        }
        $title = substr($these->title, 0, 80) . '...';
        $summary = substr($these->summary, 0, 120) . '...';
        $ul .= <<<HTML
            <li style="margin: 0; padding: 0;">
                <span style="color: #1a0dab; font-size: 20px; font-weight: 400;">{$title}</span><br>
                <span style="margin: 0; color: #121212; font-weight: 400;">{$summary}</span>
            </li>
            <br>
        HTML;
    }

    $ul .= "</ul>";

    if ($thesesCount === 0) {
        return true;
    }

    $send = sendEmail(
        $alert->email,
        $thesesCount . " nouvelle(s) thèse(s) pour «{$alert->q}»",
        <<<HTML
        Bonjour,<br><br>
        Voici les dernières thèses pour «{$alert->q}» :<br><br>
        <table width="532" style="border: 1px solid #DDD; background-color: #F7F7F7;" cellpadding="12" cellspacing="0">
            <tr>
                <th align="left">
                {$ul}
                </th>
            </tr>
        </table>
        <br>
        <a href="{$host}/?action=search&q={$alert->q}+tri:recent">TOUT VOIR ></a><br><br>
        <small style="color: grey">
            Vous avez reçu cet email car vous avez configuré une alerte pour «{$alert->q}». Si vous ne souhaitez plus recevoir d'emails, cliquez sur le lien suivant : 
            <a href="{$host}/?action=unsubscribe&redirect=1&v=1&token={$alert->unsubscribe_token}">Se désabonner</a>
        </small>
        HTML
    );

    $pdo->prepare('UPDATE alerts SET last_these_id = :last_these_id WHERE id = :id')
        ->execute([
            'last_these_id' => $theses[0]->iddoc,
            'id' => $alert->id,
        ]);

    return $send->status === 202;
}
