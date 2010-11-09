<?php

class Container extends Positionable {

    private $children = array();

    public function append(&$element) {
        if(is_subclass_of($element, "Positionable")) {
            $element->setParent($this);
            array_push($this->children, $element);
        } else {
            throw new Exception("Unknown type of element: ".get_class($element));
        }
        return $this;
    }

    public function render() {
        $this->clearRenderStack();
        foreach($this->children as $child) {
            $child->render();
            $this->addToRenderStack($child);
        }

        return parent::render();
    }


    public function getDimmension() {
        
        $boundaries = $this->getBoundaries();
        $dimmension = parent::getDimmension();
        
        if($dimmension["width"] === 0) {
            $dimmension["width"] = $boundaries["right"] - $boundaries["left"];
        }
        if($dimmension["height"] === 0) {
            $dimmension["height"] = $boundaries["bottom"] - $boundaries["top"];
        }
        return $dimmension;
    }
    
}