<?php
class ImagineBehaviourSizable extends ImagineBehaviourRenderizable {

    private
            $height = 0,
            $width = 0,
            $crop = "stretch",
            $offset = array('top' => 50, 'left' => 50),
            $multiplier = array('top' => .5, 'left' => .5);




    public function height($height = false) {

        if($height === false) {
            return $this->height;
        }
        $this->height = $height;
        $this->touch();
        return $this;
    }
    protected function configure(){
        
        $this->configureRenderOption('offset', array('top' => 0, 'left' => 0));
        $this->configureRenderOption('multiplier', array('top' => .5, 'left' => .5));
        $this->configureRenderOption('dimmension', array('height' => 0, 'width' => 0));
        parent::configure();
    }
    public function width($width = false) {

        if($width === false) {
            return $this->width;
        }
        $this->touch();
        $this->width = $width;
        return $this;
    }
    public function offsetTop($percent = 50){
        $this->offset['top'] = $percent;

        $this->touch();
        return $this;
    }
    public function offsetLeft($percent = 50){
        $this->offset['left'] = $percent;

        $this->touch();
        return $this;
    }
    public function crop() {

        $this->crop = "crop";
        $this->touch();
        return $this;
    }

    public function fit() {

        $this->crop = "fit";

        $this->touch();
        return $this;
    }

    public function stretch() {

        $this->crop = "stretch";
        $this->touch();
        return $this;
    }
    public function render() {
        $dimmension = $this->getDimmension();
        $ratio = $dimmension['height'] / $dimmension['width'];
        $rdr_dimmension = $this->renderer()->getDimmension();
        $rdr_ratio = $rdr_dimmension['height'] / $rdr_dimmension['width'];
        if($this->crop === "crop" || $this->crop === "fit") {
            
            $pixoffset = array('top' => 0, 'left' => 0);
            $ratio = $dimmension['height'] / $dimmension['width'];
            $rdr_dimmension = $this->renderer()->getDimmension();
            $rdr_ratio = $rdr_dimmension['height'] / $rdr_dimmension['width'];
            $has_horiz_offset = $ratio > $rdr_ratio;
            $multiplier = array('top' => .5, 'left' => .5);
            if($this->crop === "crop"){
                if($has_horiz_offset){
                    $propor = $this->renderer()->getHeight() / $dimmension['height'];
                    $multiplier['left'] = $this->offset['left'] / 100;
                    $pixoffset['left'] = ($dimmension['height'] / $rdr_ratio - $dimmension['width']) * $propor;
                } else {
                    $propor = $this->renderer()->getWidth() / $dimmension['width'];
                    $multiplier['top'] = $this->offset['top'] / 100;
                    $pixoffset['top'] = ($dimmension['width'] * $rdr_ratio - $dimmension['height']) * $propor;

                }
            } else if($this->crop === "fit"){

            }
            
            $this->setRenderOption('offset', $pixoffset);
            $this->setRenderOption('multiplier', $multiplier);
        }
        $this->setRenderOption('dimmension', $dimmension);
        if(false === $this->is_rendered){
            parent::render();
        }

    }
    public function getDimmension() {
        $rdim = $this->renderer()->getDimmension();
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


    public function setOffset($offset, $multiplier = array('top' => .5, 'left' => .5)) {

        $this->offset = $offset;
        $this->multiplier = $multiplier;
    }
    public function getOffset($border = false) {
        if(false === $border) return $this->offset;
        return $this->offset[$border];
    }
    public function getMultiplier($border = false){
        if(false === $border) return $this->multiplier;
        return $this->multiplier[$border];
    }
}