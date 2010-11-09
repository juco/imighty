<?php

require_once('../lib/imagine/Imagine.php');

$imagine = Imagine::getInstance(new ImagineConfiguration(array(
                "input_dir" => __DIR__."/images/",
                "output_dir" => __DIR__."/thumbs/"
)));

$include = $imagine->image()->loadFile("horizontal.jpg")->
        height(70)->
        width(50)->
        crop()->
        offsetTop(100)->
        offsetLeft(0)->
        top(20)->
        left(20);
$image = $imagine->image()->
        loadFile("vertical.jpg")->
        height("250")->
        width(200)->
        crop()->
        append($include);

$image->saveFile("vertical.jpg");

?>
<html>
    <body>
        <br />
        <img alt="vertical" src="thumbs/vertical.jpg" />
    </body>
</html>
