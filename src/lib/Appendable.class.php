<?php
class Appendable extends Positionable {
    private $children = array();
    public function append(&$element){
        if(is_subclass_of($element, "Positionable")){
            array_push($this->children, $element);
        } else {
            throw new Exception("Unknown type of element: ".get_class($element));
        }
    }
    public function render(){
        foreach(self::$children as $child){
            $this->addToRenderStack($child->render()->getImage());
        }

        return parent::render();
    }
}