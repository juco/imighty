<?php
class Container extends Positionable {

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

    public function getDimmension(){

        $dimm = parent::getDimmension();
        
        foreach($this->children as $child){
           $cpos = $child->getPosition();
           $cdimm = $child->getDimmension();
           $dimm["width"] = $dimm["width"] | $cdimm["width"];
           $dimm["height"] = $dimm["height"] | $cdimm["height"];
           
        }
        return $dimm;
    }
}