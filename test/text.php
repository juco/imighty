<?php
require_once('../lib/Imagine/Imagine.php');

Imagine::configuration(array(
                "input" => __DIR__."/images/",
                "output" => __DIR__."/thumbs/"
));
$text = "Hola <strong>gran</strong> Mundo";
$layer = Imagine::text()->
        write($text)->
        save('text.jpg');
?>
<img src='thumbs/text.jpg' alt="text"/>