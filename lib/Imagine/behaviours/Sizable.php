<?php
class Sizable extends Appendable {

    private
            $height = 0,
            $width = 0,
            $crop = "stretch";


    //Override with pre size threatment
    
    public function preRenderSize() {
        return false;
    }

    public function height($height = false) {

        if($height === false) {
            return $this->height;
        }
        $this->height = $height;
        return $this;
    }

    public function width($width = false) {

        if($width === false) {
            return $this->width;
        }
        $this->width = $width;
        return $this;
    }

    public function crop() {

        $this->crop = "crop";
        return $this;
    }

    public function fit() {

        $this->crop = "fit";
        return $this;
    }

    public function stretch() {

        $this->crop = "stretch";
        return $this;
    }

    public function getDimmension() {

        if(is_array($pre_render = $this->preRenderSize())){
            if(isset($pre_render["height"], $pre_render["width"])){
                return $pre_render;
            } else {
                throw new Exception("Wrong size format on: ".var_export($pre_render, true));
            }
        }
        
        return array(
                "width" => $this->width,
                "height" => $this->height
        );
    }


}