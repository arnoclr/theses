<?php

function dd($obj)
{
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
    die();
}
