<?php

use App\Model\These;
use Smalot\PdfParser\Parser;

$nnt = $_GET['nnt'];

$parser = new Parser();

$images = getOrCache("pdf.images." . $nnt, 60 * 24 * 7, function () use ($parser, $nnt) {

    $host = These::getOnlineLinkFromNNT($nnt);

    $ch = curl_init($host);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $headers = substr($response, 0, strpos($response, "\r\n\r\n"));
    $url = null;

    if (strpos($headers, 'Content-Type: text/html') !== false) {
        $pageContent = substr($response, strpos($response, "\r\n\r\n") + 4);
        $url = These::getPDFFromHTML($pageContent);
    }

    if (strpos($headers, 'Content-Type: application/pdf') !== false) {
        $url = $host;
    }

    if ($url === null) {
        return [];
    }

    $pdf = $parser->parseFile($url);

    return $pdf->getObjectsByType('XObject', 'Image');
});

foreach (array_slice($images, 1, 32) as $image) {
    $base64 = base64_encode($image->getContent());
    echo <<<HTML
        <img height="110" 
            onload="this.parentNode.classList.add('loaded'); this.parentNode.style.height = '110px';"
            onerror="this.parentNode.removeChild(this)"
            src="data:image/jpg;base64,$base64" />
    HTML;
}
