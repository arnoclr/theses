<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theses.fr Visualisation</title>
    <meta name="theme-color" content="#29B6F6">
    <meta name="apple-mobile-web-app-status-bar-style" content="#29B6F6">
    <meta name="msapplication-navbutton-color" content="#29B6F6">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/beercss@2.2.11/dist/cdn/beer.min.css" integrity="sha256-lYnQDpcf+FFMWvFyNlfYM5Zis7/ENdFurMo6UK58k4E=" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/style.css">
</head>

<body>

    <header class="primary-container fixed js-navbar small-elevate">
        <nav>
            <button class="circle transparent" data-ui="#modal-navigation-drawer">
                <i>menu</i>
            </button>
            <div class="max"></div>
            <form method="get" action="/" class="field prefix plain js-nav-search">
                <i>search</i>
                <input type="hidden" name="action" value="search">
                <input type="search" name="q" placeholder="Découvrir des thèses">
            </form>
            <div class="max"></div>
        </nav>
    </header>

    <div class="modal left" id="modal-navigation-drawer">
    </div>