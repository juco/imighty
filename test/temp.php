<?php

require_once('../lib/Imagine.php');
Imagine::register();

$layer = Imagine::layer();
$image = Imagine::image()->
        width("200")->
        height("300");


$layer->append($image);

var_dump($layer->getDimmension());
