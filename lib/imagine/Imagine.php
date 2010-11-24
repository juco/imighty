<?php

require_once("Core.php");

class Imagine extends ImagineCore {
    
    public function configureTools() {
        return array(
                "layer" => "ImagineLayerLayer",
                "image" => "ImagineLayerImage",
                "text" => "ImagineLayerText"
        );
    }

    public function configureFilters(){
        return array(
          'grayscale' => 'ImagineFilterGrayscale'
        );
    }
    public function configureFonts(){
        return array(
            'arial' => 'arial',
            'arial_bold' => 'arialbd',
            'arial_black' => 'arialblk',
            'arial_italic' => 'ariali',
            'arial_bold_italic' => 'arialbi'
        );
    }
    
    public function configureAutoload(){
        return array();
    }
}