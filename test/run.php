<?php



$pub_dir = sfConfig::get("sf_web_dir");
$output_dir = $pub_dir."/images/thumbnails";

ImLayer::registerFont("arial_italic_bold", array(
        "file" => "Arial.ttf",
        "default_style" => "italic bold 12px/16px",
        "default_color" => "#000"
));

// Coge la primera fuente registrada por defecto.

$text = ImLayer::text("Hola, esto es texto")->
        bottom(10)->
        right(0)->
        padding(10)->
        position("absolute")->
        background("#66ffffff");

$im = ImLayer::image($pub_dir."/images/test_0.jpg")->
        width(300)->
        height(300)->
        crop()-> // fit()
        append($text)->
        output();



//
// 1.- cargar una imagen
// 2.- convertir en una imagen de 640 de ancho y 300 de máximo de alto



// Configure
$im = ImVerse::set("output_dir", $output_dir);

// Tests
// Cargar una imagen y cambiar el tamaño a un ancho concreto con un máximo de alto
$im = ImVerse::create()->
        background($pub_dir."/images/test_0.jpg")->
        width("640")->
        height("300")->
        writeFile("result/test_0.jpg"); // process implícito


/*
        // Cargar una imagen y cambiar el tamaño a un ancho concreto, con un máximo de alto, alineada arriba y cortando lo que sobra.
        // Si la imagen es más pequeña, se aumentará hasta que tenga un ancho fijo.
        $im = ImVerse::create()->
                background($pub_dir."/images/test_0.jpg")->
                width("640")->
                height("300")->
                crop("vertical")->
                magnifize()-> // por si la imagen es más pequeña
                align("top")-> // center, vcenter, hcenter, left, right, bottom
                writeImage("result/test_1.jpg");

        // Cargar una imagen y cambiar el tamaño a un ancho y alto concretos, alineada al centro y cortando lo que sobra.
        // La imagen se reducirá hasta entrar dentro de uno de los límites, si es más pequeña quedará centrada.
        $im = ImVerse::create()->
                background($pub_dir."/images/test_0.jpg")->
                width("640")->
                height("300")->
                writeImage("result/test_2.jpg");

        // Crear un texto en una imagen de un ancho concreto
        // La imagen se reducirá hasta entrar dentro de uno de los límites, si es más pequeña quedará centrada.
        $im = ImVerse::create()->
                background("#ffeedd")->
                setParragraph("Esto sí que es una sorpresa\nLo atestiguo y meto un intro de verdad.
Esta es una nueva línea.")->
                width("640")->
                writeImage("result/test_3.jpg");
         * 
*/
