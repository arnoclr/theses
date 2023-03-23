<?php

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

function getOrCache($key, $minutes = 60, $closure)
{
    $filename = ROOT . '/tmp/' . sha1($key);
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        $data = unserialize($data);
        $time = filemtime($filename);
        if ($time + $minutes * 60 > time()) {
            return $data;
        }
    }
    $data = $closure();
    if (!file_exists(ROOT . '/tmp')) {
        mkdir(ROOT . '/tmp', 0777, true);
    }
    file_put_contents($filename, serialize($data));
    return $data;
}

function sendEmail($to, $subject, $HTML)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $_ENV['EMAIL_API_URL']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "to" => $to,
        "subject" => $subject,
        "HTML" => $HTML,
        "name" => "Information Thèses",
        "from" => "informationtheses",
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $_ENV['EMAIL_BEARER'],
    ]);
    // FIXME: Trouver un moyen de faire sans
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $content = curl_exec($ch);
    curl_close($ch);

    return json_decode($content, false);
}

function dd($obj)
{
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
    die();
}
