Imagine. The easy-to-use gd wrapper for image manipulation.
===

To load
---
```php
include('path-to-imagine/Imagine.php');
Imagine::configuration(array(
    "input" => PATH_TO_PUBLIC_DIR."/images/",
    "output" => PATH_TO_DIR."/thumbs/"
));
```

To use (image)
---
```php
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
```

To use (text)
---
```php
$html = <<<HTML
<h1>HTML Ipsum Presents</h1>
<p>Hello, <br />Two line parragraph</p>
<h1>Other Title</h1>
<p> <strong>Pellentesque habitant morbi tristique</strong> senectus
et netus et malesuada fames ac turpis egestas. <em>Aenean ultricies mi vitae est.</em>.
<code>commodo vitae</code> <a href="#">orci, sagittis Donec non enime</a>.</p>';
HTML;


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
```
