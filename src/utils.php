<?php

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

// TTL in minutes
function getOrCache($key, $ttl = 60, $closure)
{
    $filename = ROOT . '/tmp/' . $key;
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        $data = unserialize($data);
        $time = filemtime($filename);
        if ($time + $ttl * 60 > time()) {
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
    $url = "https://delivery.omsistuff.fr";
    $data = array(
        "authorization" => "",
        "to" => $to,
        "subject" => $subject,
        "HTML" => $HTML
    );

    $options = array(
        "http" => array(
            "header" => "Content-type: application/json",
            "method" => "POST",
            "content" => json_encode($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
}

function dd($obj)
{
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
    die();
}
