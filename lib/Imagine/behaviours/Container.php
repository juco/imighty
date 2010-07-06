<?php
class Container extends Positionable {

    private $children = array();

    public function append(&$element) {
        if(is_subclass_of($element, "Positionable")) {
            array_push($this->children, $element);
        } else {
            throw new Exception("Unknown type of element: ".get_class($element));
        }
    }

    public function render() {
        $this->clearRenderStack();
        foreach($this->children as $child) {
            $this->addToRenderStack($child->render());
        }

        return parent::render();
    }

    public function getBoundaries() {
        
        $boundaries = parent::getBoundaries();
        $borders = $this->getBorders();
        foreach($this->children as $child) {
            $child_boundaries = $child->getBoundaries();
            foreach(array_keys($borders) as $border) {
                if($this->cmp($boundaries[$border], $child_boundaries[$border]) === $borders[$border]["operator"]) {
                    $boundaries[$border] = $child_boundaries[$border];
                }
            }
        }
        return $boundaries;
    }

    public function getDimmension() {
  
        $boundaries = self::getBoundaries();
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