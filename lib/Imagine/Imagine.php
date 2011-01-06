<?php

require_once("Core.php"); 

class Imagine extends ImagineCore {
    
    public static function configureTools() {
        return array(
                "layer" => "ImagineLayerLayer",
                "image" => "ImagineLayerImage",
                "text" => "ImagineLayerText"
        );
    }

    public static function configureFilters(){
        return array(
          'grayscale' => 'ImagineFilterGrayscale'
        );
    }
    public static function configureFonts(){
        return array(
            'arial' => 'arial',
            'arial_bold' => 'arialbd',
            'arial_black' => 'arialblk',
            'arial_italic' => 'ariali',
            'arial_bold_italic' => 'arialbi'
        );
    }
    
    public static function configureAutoload(){
        return array();
    }
}
