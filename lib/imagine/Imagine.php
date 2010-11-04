<?php

require_once("ImagineCore.php");
require_once("ImagineConfiguration.php");

class Imagine extends ImagineCore {
    
    public function configureTools($tools = array()) {
        
        $tools = array(
                "layer" => "ImagineLayer",
                "image" => "ImagineImage",
                "text" => "ImagineText"
        );
        return $tools;
    }
    
    public function configureAutoload($dirs = array()){

        return $dirs;
    }
}