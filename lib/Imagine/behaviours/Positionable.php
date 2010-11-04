<?php

class Positionable extends Sizable {
    protected
            $top = 0,
            $left = 0,
            $right = false,
            $bottom = false,
            $position = "relative",
            $parent = false;

    private static $borders = array(
            "top" => array(
                            "opposite" => "bottom",
                            "orientation" => "height",
                            "operator" => 1
            ),
            "bottom" => array(
                            "opposite" => "top",
                            "orientation" => "height",
                            "operator" => -1
            ),
            "left" => array(
                            "opposite" => "right",
                            "orientation" => "width",
                            "operator" => 1
            ),
            "right" => array(
                            "opposite" => "left",
                            "orientation" => "width",
                            "operator" => -1
            )
    );


    /*
     *
    */
    // TODO: This should be not magic

    public function  __call($border,  $arguments) {

        if(in_array($border, array_keys(self::$borders))) {

            if(sizeof($arguments) > 1) {
                throw new Exception($border." accepts only 1 argument.");
            }

            $value = false;
            if(sizeof($arguments) === 1) {
                $value = $arguments[0];
            }
            if($value === false) {
                return $this->$border;
            }

            $opp = self::$borders[$border]["opposite"];
            $this->$opp = false;
            $this->$border = $value;
            return $this;
        }
    }
    public function getParent() {
        return $this->parent;
    }
    public function setParent($parent) {
        $this->parent = $parent;
    }
    public function hasParent() {
        return (false !== $this->parent);
    }
    public function getPosition() {
        return array(
                "left" => $this->left,
                "right" => $this->right,
                "top" => $this->top,
                "bottom" => $this->bottom
        );
    }

    public function getBoundaries() {
        if(!$this->hasParent()) {
            return array(
                    "left" => 0,
                    "right" => $this->width(),
                    "top" => 0,
                    "bottom" => $this->height()
            );
        }
        $boundaries = array();
        $borders = $this->getBorders();

        $pdim = $this->getParent()->getDimmension();
        $dim = parent::getDimmension();
        foreach($borders as $border => $options) {
            if(isset($borders[$border])) {
                $opposite = $temp = $options["opposite"];
                if(false === $this->$border) {
                    $opposite = $border;
                    $border = $temp;
                }
                unset($temp);
                $boundaries[$border] = $this->$border;
                $add = $this->$border + $dim[$options['orientation']];
                $boundaries[$opposite] = $pdim[$options["orientation"]] - $add  * $options["operator"];
                unset($borders[$border], $borders[$opposite]);
            }
        }

        return $boundaries;
    }
    public function render() {
        if($this->hasParent()) {
            $boundaries = $this->getBoundaries();
            $this->getRenderer()->setPosition($boundaries);
        }
        parent::render();
    }
    public function getBorders() {
        return self::$borders;
    }

    protected function cmp($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}