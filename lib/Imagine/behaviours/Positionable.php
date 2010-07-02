<?php

class Positionable extends Sizable {
    protected
            $top = 0,
            $left = 0,
            $right = false,
            $bottom = false,
            $position = "relative";

    private $opposites = array(
            "top" => "bottom",
            "bottom" => "top",
            "left" => "right",
            "right" => "left"
    );


    /*
     *
     */
    // TODO: This should be static
    
    public function  __call($border,  $arguments) {

        if(in_array($name, array_keys($this->opposites))) {

            if(sizeof($arguments > 1)) {
                throw new Exception($border." accepts only 1 argument.");
            }

            $value = false;
            if(sizeof($arguments) === 1) {
                $value = $arguments[0];
            }

            if($value === false) {
                return $this->$border;
            }
            $opp = $this->opposites[$border];
            $this->$opp = false;
            $this->$border = $value;
            return $this;
        }
    }

    public function getPosition() {
        return array(
                "left" => $this->left,
                "right" => $this->right
        );
    }
}