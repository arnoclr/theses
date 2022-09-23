<?php

// autoload php classes
foreach (glob("app/class/*.php") as $class) {
    require_once $class;
}

$searcher = new Searcher(null);
echo $searcher->before(2019)->after(2018)->fromAuthor('John')->limit(5)->sortedAlphabetically()->_debug();
