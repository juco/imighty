<?php

require_once('../lib/imagine/Imagine.php');

Imagine::configuration(array(
                "input" => __DIR__."/images/",
                "output" => __DIR__."/thumbs/"
));



$filter = Imagine::filter('grayscale')->
        white('#ff0000')->
        black('#000')->
        color('#ff0000');

$include = Imagine::image()->
        load("horizontal.jpg")->
        height(70)->
        width(50)->
        crop()->
        offsetTop(100)->
        top(20)->
        left(20)->
        apply($filter);

$image = Imagine::image()->
        load("vertical.jpg")->
        height("250")->
        width(200)->
        crop()->
        append($include);

$image->save("vertical.jpg");
?>
<html>
    <body>
        <img alt="vertical" src="thumbs/vertical.jpg" />
<?php

ob_flush();
flush();

$include->
        width(100)->
        load('vertical.jpg');
$include2 = Imagine::image()->
        load("horizontal.jpg")->
        height(70)->
        width(50)->
        crop()->
        offsetTop(100)->
        top(30)->
        left(30)->
        apply($filter);
$image->
        width(250)->
        height(0)->
        append($include2);

$include2->down($include);

$include->down($include2);

$filter->color(false);

$image->save("vertical2.jpg");


?>

        <br />
        <img alt="vertical" src="thumbs/vertical2.jpg" />
        <br />
        <?php echo memory_get_usage(true); ?>
    </body>
</html>
