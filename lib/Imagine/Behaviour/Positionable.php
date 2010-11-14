<?php

abstract class ImagineBehaviourPositionable extends ImagineBehaviourSizable {

    abstract public function getParent();
    abstract public function setParent($parent);

    protected
            $top = 0,
            $left = 0,
            $right = false,
            $bottom = false,
            $position = "relative";

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
    protected function configure(){
        $this->configureRenderOption('boundaries');
        parent::configure();
    }

    public function top(){
        return $this->border('top', func_get_args());
    }
    public function left(){
        return $this->border('left', func_get_args());
    }
    public function right(){
        return $this->border('right', func_get_args());
    }
    public function bottom(){
        return $this->border('bottom', func_get_args());
    }

    public function border($border,  $arguments) {

        if(in_array($border, array_keys(self::$borders))) {

            if(sizeof($arguments) > 1) {
                throw new Exception($border." accepts only 1 argument.");
            }
            $this->touch(); 
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
        } else {
            throw new Exception($border.' is not a property');
        }
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
            $this->setRenderOption("boundaries", $this->getBoundaries());
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