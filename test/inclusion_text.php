<?php

require_once('../lib/Imagine/Imagine.php');

Imagine::configuration(array(
                "input" => __DIR__."/images/",
                "output" => __DIR__."/thumbs/"
));


$html = '<h1>HTML Ipsum Presents</h1><p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>';


$text = Imagine::text()->
        write($html)->
        style('default',    array('size' => 12, 'line_height' => 20, 'background' => '#9ff'))->
        style('h1',         array('display' => 'block', 'size' => 24, 'line_height' => 40, 'font_weight' => 'bold'))->
        style('strong',     array('font_weight' => 'bold'));

$image = Imagine::image()->
        load("vertical.jpg")->
        width(200)->
        crop()->
        append($text);

$image->save("vertical.jpg");
?>
<html>
    <body>
        <img alt="vertical" src="thumbs/vertical.jpg" />

        <br />

        <?php echo memory_get_usage(true); ?>
    </body>
</html>

