<?php

require_once("Core.php");

class Imagine extends ImagineCore {
    
    public function configureTools() {
        return array(
                "layer" => "ImagineToolLayer",
                "image" => "ImagineToolImage",
                "text" => "ImagineToolText"
        );
    }

    public function configureFilters(){
        return array(
          'grayscale' => 'ImagineFilterGrayscale'
        );
    }
    
    public function configureAutoload(){
        return array();
    }
}