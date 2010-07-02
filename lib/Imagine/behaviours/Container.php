<?php
abstract class Container extends Positionable {

    private $children = array();

    public function append(&$element) {
        if(is_subclass_of($element, "Positionable")) {
            array_push($this->children, $element);
        } else {
            throw new Exception("Unknown type of element: ".get_class($element));
        }
    }

    public function render() {
        foreach(self::$children as $child) {
            $this->addToRenderStack($child->render()->getImage());
        }

        return parent::render();
    }

    public function getBoundaries() {

        $borders = $this->getBorders();
        foreach($this->children as $child) {
            $child_boundaries = $child->getBoundaries();
            foreach(array_keys($borders) as $border) {
                if($this->cmp($boundaries[$border], $child_boundaries[$border]) === $borders[$border]["operator"]) {
                    $boundaries[$border] = $child_boundaries[$border];
                }
            }
            $dimm["width"] = $dimm["width"]?$dimm["width"]:($cdimm["width"] + $cpos["left"]);
            $dimm["height"] = $dimm["height"]?$dimm["height"]:($cdimm["height"] + $cpos["top"]);

        }
        return $boundaries;
    }

    public function getDimmension() {
        
        $dimmension = parent::getDimmension();
        $boundaries = $this->getBoundaries();

        if($dimmension["width"] == 0) {
            $dimmension["width"] = $boundaries["left"] - $boundaries["right"];
        }
        if($dimmension["height"] == 0) {
            $dimmension["height"] = $boundaries["top"] - $boundaries["bottom"];
        }
    }
    abstract protected function prepareDimmension();
}