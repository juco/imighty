<?php
require_once('../lib/Imagine/Imagine.php');

$html = '
    <h1>HTML Ipsum Presents</h1>
    <p>Hola, <br />Párrafo con dos líneas</p>
    <h1>Otro título</h1>
    <p> <strong>Pellentesque habitant morbi tristique</strong> senectus
    et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam,
    feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero
    sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em>
    Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper 
    pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>,
    ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum,
    eros ipsum rutrum <a href="#">orci, sagittis Donec non enime</a> sempus lacus enim ac dui.
     in turpis pulvinar facilisis. Ut felis.</p>';

Imagine::configuration(array(
        "input" => __DIR__."/images/",
        "output" => __DIR__."/thumbs/"
));
$text = Imagine::text()->
        write($html)->
        style('default', array('size' => 16, 'line_height' => 20, 'background' => '#9ff'))->
        style('h1', array('display' => 'block', 'size' => 32, 'line_height' => 40, 'font_weight' => 'bold'))->
        style('p', array('display' => 'block'))->
        style('em', array())->
        style('a', array('font_weight' => 'bold'))->
        style('code', array())->
        style('strong', array('font_weight' => 'bold', 'background' => '#f00'))->
        width(500)->
        save('text.jpg');
?>
<img src='thumbs/text.jpg' alt="text"/><br /><br />

<style>
    div {
        width: 500px;
        font-family: sans-serif;
        background: #9ff;
    }
    h1 {
        margin: 0px;
    }
    p {
        margin:0px;
    }
</style>

<div>
    <?= utf8_decode($html); ?>
</div>