<?php

require_once('../lib/Imagine.php');
Imagine::register();

$layer = Imagine::layer();

$image = Imagine::image()->
        width("250")->
        height("300")->
        bottom(2)->
        right(3);


$layer->append($image);

var_dump($layer->getDimmension());
