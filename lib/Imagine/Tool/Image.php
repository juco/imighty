<?php

class ImagineToolImage extends ImagineBehaviourContainer {
    private $image = false;
    public function  __construct($imagine, $file_name = false) {
        parent::__construct($imagine);
        
        if($file_name != false){
            $this->image = $this->renderer()->loadFile($file_name);
        }
    }
    
    public function load ($file_name = false) {
        $this->touch();
        $this->renderer()->loadFile($file_name);
        return $this;
    }
}
