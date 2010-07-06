<?php

class ImagineImage extends Container {
    private $image = false;
    public function  __construct($file_name = false) {
        parent::__construct();
        
        if($file_name != false){
            $this->image = $this->getRenderer()->loadFile($file_name);
        }
    }
    
    public function loadFile ($file_name = false) {
        $this->getRenderer()->loadFile($file_name);
        return $this;
    }
}
