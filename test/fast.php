<?php

require_once('../lib/Imagine/Imagine.php');

Imagine::configuration(array(
        "input" => __DIR__."/images/",
        "output" => __DIR__."/thumbs/"
));


$html = '<h1>HTML Ipsum Presents</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ipsum mi, tempus id imperdiet tincidunt, ultricies at justo. Ut scelerisque nunc augue. Duis et felis odio, vel volutpat urna. Curabitur interdum odio vitae nisi ultrices ullamcorper. Aliquam consectetur lectus ac justo semper tincidunt semper sapien ornare. Nullam et felis purus. Phasellus vulputate tempus nulla quis vehicula. Morbi scelerisque eros quis orci rutrum sed rutrum neque aliquam. Vestibulum erat nibh, egestas non sagittis vel, tempor nec metus. Cras aliquet tellus ac quam porta egestas. Ut sit amet sem eu urna auctor iaculis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris id portas magna. Morbi vitae vehicula lectus.</p>';


$text = Imagine::text()->
        write($html)->
        width('100%')->
        padding('left', 20)->
        padding('right', 20)->
        padding('top', 20)->
        padding('bottom', 20)->
        margin('right', 20)->
        margin('bottom', 20)->
        margin('left', 20)->
        style('default', array('size' => 16, 'line_height' => 20))->
        style('h1', array('display' => 'block', 'size' => 32, 'line_height' => 40, 'font_weight' => 'bold', 'margin_bottom' => 11, 'margin_top' => 11))->
        style('p', array('display' => 'block'))->
        style('em', array())->
        style('a', array('font_weight' => 'bold'))->
        style('code', array())->
        style('strong', array('font_weight' => 'bold', 'background' => '#f00'));

$include = Imagine::image()->
        load("horizontal.jpg")->
        width('100%')->
        background('#000')->
        padding('left', 20)->
        padding('right', 20)->
        padding('top', 20)->
        padding('bottom', 20)->
        crop();

$image = Imagine::image()->
        load("vertical.jpg")->
        append($text)->
        append($include)->
        width('500')->
        crop();

//$include->down($text);

$image->save("vertical.jpg");
?>
<html>
    <body>
        <img alt="vertical" src="thumbs/vertical.jpg" />

        <br />

        <?php echo memory_get_usage(true); ?>
    </body>
</html>

