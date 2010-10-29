<?php

require_once('../lib/Imagine.php');



$imagine_configuration = new ImagineConfiguration(array(
                "input_dir" => __DIR__."/images/",
                "output_dir" => __DIR__."/thumbs/"
));

Imagine::register($imagine_configuration);


//$layer = Imagine::layer();

$image = Imagine::image()->
loadFile("vertical.jpg")->
width("250")->
        height("300")->
        bottom(2)->
        right(3);


//$layer->append($image);

$image->saveFile("vertical.jpg");

//var_dump($image->getDimmension());
//var_dump($layer->getBoundaries());
