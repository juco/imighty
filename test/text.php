<?php
require_once('../lib/Imagine/Imagine.php');

Imagine::configuration(array(
                "input" => __DIR__."/images/",
                "output" => __DIR__."/thumbs/"
));
$html = "Hola <strong class='rojo amarillo' id='magnifico'>gran</strong> Mundo
    <p class='guay'> y esto es un párrafo! <strong>go</strong></p>";
$text = Imagine::text()->
        write($html)->
        style('strong', array('font_weight' => 'bold'))->
        save('text.jpg');
?>
<img src='thumbs/text.jpg' alt="text"/>