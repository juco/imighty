<?php
class Sizable extends Appendable {

    private
            $height = 0,
            $width = 0,
            $crop = "stretch";

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

        return array(
                "width" => $this->width,
                "height" => $this->height
        );
    }


}