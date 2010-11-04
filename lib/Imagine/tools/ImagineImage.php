<?php

class ImagineImage extends Container {
    private $image = false;
    public function  __construct($imagine, $file_name = false) {
        parent::__construct($imagine);
        
        if($file_name != false){
            $this->image = $this->getRenderer()->loadFile($file_name);
        }
    }
    
    public function loadFile ($file_name = false) {
        $this->getRenderer()->loadFile($file_name);
        return $this;
    }
}
