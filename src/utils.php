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

function getCache(string $key)
{
    $filename = ROOT . '/tmp/' . sha1($key);
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        $data = unserialize($data);
        return $data;
    }
    return null;
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

function getWikipediaDataFor($queryWithMultipleWords)
{
    return getOrCache($queryWithMultipleWords, 60 * 24, function () use ($queryWithMultipleWords) {
        $query = str_replace(' ', '_', $queryWithMultipleWords);
        $query = urlencode($query);
        $params = array(
            'action' => 'query',
            'format' => 'json',
            'prop' => 'extracts|pageimages',
            'exintro' => '',
            'explaintext' => '',
            'exsentences' => 1,
            'titles' => $query,
            'pithumbsize' => 500
        );
        $url = 'https://fr.wikipedia.org/w/api.php?' . http_build_query($params);
        $content = @file_get_contents($url);
        if ($content === false) {
            return null;
        }
        $content = json_decode($content, true);
        $pages = $content['query']['pages'];
        if (count($pages) === 0) {
            return null;
        }
        $data = array_shift($pages);
        if (isset($data['title']) === false || isset($data['extract']) === false) {
            return null;
        }
        return $data;
    });
}

function compressBase64Image(string $base64, int $height): ?string
{
    $content = file_get_contents($base64);
    $image = @imagecreatefromstring($content);

    if ($image === false) {
        return null;
    }

    $width = imagesx($image);
    $height = imagesy($image);

    $new_height = 110;
    $new_width = $width * ($new_height / $height);

    $new_image = imagecreatetruecolor($new_width, $new_height);

    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    ob_start();
    imagejpeg($new_image, null, 75);
    $new_image_base64 = base64_encode(ob_get_clean());

    imagedestroy($image);
    imagedestroy($new_image);

    return "data:image/jpeg;base64,{$new_image_base64}";
}

function dd($obj)
{
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
    die();
}
