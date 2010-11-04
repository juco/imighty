<?php

require_once('../lib/imagine/Imagine.php');

$imagine = Imagine::getInstance(new ImagineConfiguration(array(
                "input_dir" => __DIR__."/images/",
                "output_dir" => __DIR__."/thumbs/"
)));

$include = $imagine->image()->loadFile("vertical.jpg")->
        height(70)->
        width(70)->
        crop()->
        top(20)->
        left(20);
$image = $imagine->image()->
        loadFile("horizontal.jpg")->
        height("250")->

        append($include);

$image->saveFile("vertical.jpg");

?>
<html>
    <body>
        <br />
        <img alt="vertical" src="thumbs/vertical.jpg" />
    </body>
</html>
