<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theses.fr Visualisation</title>
    <meta name="theme-color" content="#c4e7ff">
    <meta name="apple-mobile-web-app-status-bar-style" content="#c4e7ff">
    <meta name="msapplication-navbutton-color" content="#c4e7ff">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/beercss@2.2.11/dist/cdn/beer.min.css" integrity="sha256-lYnQDpcf+FFMWvFyNlfYM5Zis7/ENdFurMo6UK58k4E=" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/style.css?ac=9">
</head>

<body>

    <header class="fixed">
        <nav>
            <form method="get" action="/" class="js-nav-search">
                <label class="field prefix plain">
                    <i>search</i>
                    <input type="hidden" name="action" value="search">
                    <input type="search" name="q" spellcheck="false" placeholder="Découvrir des thèses" value="<?= htmlspecialchars($q ?? '') ?>">
                </label>
            </form>
        </nav>
    </header>