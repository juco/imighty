<?php

class ImagineLayerLayer extends ImagineBehaviourContainer {
    protected $background = 'transparent';
    public function background($color = false){
        if(false === $color){
            return $this->background;
        }
        $this->background = $color;
        return $this;
    }
}