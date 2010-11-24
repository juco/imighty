<?php

require_once('../lib/Imagine/Imagine.php');

Imagine::configuration(array(
        "input" => __DIR__."/images/",
        "output" => __DIR__."/thumbs/"
));


$html = '<h1>HTML Ipsum Presents</h1>';


$text = Imagine::text()->
        write($html)->
        bottom(0)->
        padding('left', 20)->
        background('transparent')->
        style('default',    array('size' => 12, 'line_height' => 20))->
        style('h1',         array('display' => 'block', 'size' => 24, 'line_height' => 40, 'font_weight' => 'bold'))->
        style('strong',     array('font_weight' => 'bold'));

$image = Imagine::image()->
        load("horizontal.jpg")->
        append($text)->
        width(500)->
        crop();

$image->save("vertical.jpg");
?>
<html>
    <body>
        <img alt="vertical" src="thumbs/vertical.jpg" />

        <br />

        <?php echo memory_get_usage(true); ?>
    </body>
</html>

