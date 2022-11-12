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
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>

    <header class="primary-container fixed js-navbar small-elevate">
        <nav>
            <button class="circle transparent" data-ui="#modal-navigation-drawer">
                <i>menu</i>
            </button>
            <form method="get" action="/" class="field prefix plain js-nav-search">
                <i>search</i>
                <input type="hidden" name="action" value="search">
                <input type="search" name="q" placeholder="Découvrir des thèses" value="<?= htmlspecialchars($q ?? '') ?>">
            </form>
        </nav>
    </header>

    <div class="modal left" id="modal-navigation-drawer">
        <header class="fixed">
            <nav>
                <h5 class="max">Theses</h5>
            </nav>
        </header>
        <a href="/" class="row round">
            <span>Accueil</span>
        </a>
        <a href="https://github.com/arnoclr/theses" class="row round">
            <span>Code source</span>
        </a>
        <a href="https://www.data.gouv.fr/fr/datasets/theses-soutenues-en-france-depuis-1985/" class="row round">
            <span>Données publiques</span>
        </a>
    </div>