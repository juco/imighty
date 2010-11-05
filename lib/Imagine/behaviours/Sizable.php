<?php
class Sizable extends Renderizable {

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
    public function render() {
        $dimmension = $this->getDimmension();
        $ratio = $dimmension['height'] / $dimmension['width'];
        $rdr_dimmension = $this->getRenderer()->getDimmension();
        $rdr_ratio = $rdr_dimmension['height'] / $rdr_dimmension['width'];
        if($this->crop === "crop" || $this->crop === "fit") {
            
            $offset = array('top' => 0, 'left' => 0);
            $ratio = $dimmension['height'] / $dimmension['width'];
            $rdr_dimmension = $this->getRenderer()->getDimmension();
            $rdr_ratio = $rdr_dimmension['height'] / $rdr_dimmension['width'];
            $has_horiz_offset = $ratio > $rdr_ratio;
            if($this->crop === "crop"){
                if($has_horiz_offset){
                    $propor = $this->getRenderer()->getHeight() / $dimmension['height'];
                    $offset['left'] = ($dimmension['height'] / $rdr_ratio - $dimmension['width']) * $propor;
                } else {
                    echo "ratio: ".$rdr_ratio."<br />";
                    echo "dimentionh: ".$dimmension['height'];
                    
                    $propor = $this->getRenderer()->getWidth() / $dimmension['width'];
                    echo "propor: ".$propor;
                    $offset['top'] = ($dimmension['width'] * $rdr_ratio - $dimmension['height']) * $propor;
                }
            } else if($this->crop === "fit"){

            }
            
            $this->getRenderer()->setOffset($offset);
        }
        $this->getRenderer()->setDimmension($dimmension);
        parent::render();

    }
    public function getDimmension() {
        $rdim = $this->getRenderer()->getDimmension();
        if(!$this->width && !$this->height) {
            return $rdim;
        }

        $prop = $rdim['height'] / $rdim['width'];

        if(!$this->width) {
            $this->width = $this->height / $prop;
        } else if(!$this->height) {
            $this->height = $this->width * $prop;
        }
        return array(
                "width" => $this->width,
                "height" => $this->height
        );
    }


}