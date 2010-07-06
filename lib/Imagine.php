<?php

require_once(dirname(__FILE__)."/Imagine/ImagineCore.php");
require_once(dirname(__FILE__)."/Imagine/ImagineConfiguration.php");

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