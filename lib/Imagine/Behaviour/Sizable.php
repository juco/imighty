<?php
class ImagineBehaviourSizable extends ImagineBehaviourRenderizable {

    private
            $width = 0,
            $height = 0,
            $crop = "stretch",
            $offset = array('top' => 50, 'left' => 50),
            $multiplier = array('top' => .5, 'left' => .5),
            $margins = array('top' => 0, 'left' => 0, 'bottom' => 0, 'right' => 0),
            $paddings = array('top' => 0, 'left' => 0, 'bottom' => 0, 'right' => 0);




    public function height($height = false) {

        if($height === false) {
            return $this->height;
        }
        $this->height = $height;
        $this->touch();
        return $this;
    }
    protected function configure() {

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
    public function offsetTop($percent = 50) {
        $this->offset['top'] = $percent;

        $this->touch();
        return $this;
    }
    public function offsetLeft($percent = 50) {
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
        $rdr_dimmension = $this->getRenderer()->getDimmension();
        $rdr_ratio = $rdr_dimmension['height'] / $rdr_dimmension['width'];
        if($this->crop === "crop" || $this->crop === "fit") {

            $pixoffset = array('top' => 0, 'left' => 0);
            $ratio = $dimmension['height'] / $dimmension['width'];
            $rdr_dimmension = $this->getRenderer()->getDimmension();
            $rdr_ratio = $rdr_dimmension['height'] / $rdr_dimmension['width'];
            $has_horiz_offset = $ratio > $rdr_ratio;
            $multiplier = array('top' => .5, 'left' => .5);
            if($this->crop === "crop") {
                if($has_horiz_offset) {
                    $propor = $this->getRenderer()->getHeight() / $dimmension['height'];
                    $multiplier['left'] = $this->offset['left'] / 100;
                    $pixoffset['left'] = ($dimmension['height'] / $rdr_ratio - $dimmension['width']) * $propor;
                } else {
                    $propor = $this->getRenderer()->getWidth() / $dimmension['width'];
                    $multiplier['top'] = $this->offset['top'] / 100;
                    $pixoffset['top'] = ($dimmension['width'] * $rdr_ratio - $dimmension['height']) * $propor;

                }
            } else if($this->crop === "fit") {
                // TODO: this possibility
            }

            $this->setRenderOption('offset', $pixoffset);
            $this->setRenderOption('multiplier', $multiplier);
        }
        $this->setRenderOption('dimmension', $dimmension);
        if(false === $this->is_rendered) {
            parent::render();
        }

    }
    public function margin() {
        $args = func_get_args();
        if(!sizeof($args)) {
            return $this->margins;
        } else if(sizeof($args) == 1 && is_string($args[0])) {
            return $this->margins[$args[0]];
        } else if(sizeof($args)==2 && is_string($args[0]) && is_int($args[1])) {
            $this->margins[$args[0]] = $args[1];
        }
        return $this;
    }
    public function padding() {
        $args = func_get_args();
        if(!sizeof($args)) {
            return $this->paddings;
        } else if(sizeof($args) == 1 && is_string($args[0])) {
            return $this->paddings[$args[0]];
        } else if(sizeof($args)==2 && is_string($args[0]) && is_int($args[1])) {
            $this->paddings[$args[0]] = $args[1];
        }
        return $this;
    }
    public function getDimmension() {
        $rdim = $this->getRenderer()->getDimmension();
        if(!$this->width && !$this->height) {
            return $rdim;
        }



        $will_span = array();
        if(is_string($this->width) && $this->width == '100%') {
            $will_span[] = 'width';
        }
        if(is_string($this->height) && $this->height == '100%') {
            $will_span[] = 'height';
        }

        $width = $height = 0;
        if(sizeof($will_span)) {
            if(!$this->hasParent()) {
                throw new Exception("No parent for this 100% dimmension.");
            } else {
                $dimmension = $this->getParent()->getDimmension();
                $map = array('width' => array('left', 'right'), 'height' => array('top', 'bottom'));
                foreach($will_span as $axis) {

                    $$axis = (int) $dimmension[$axis];
                    foreach($map[$axis] as $border){
                        $$axis -=  $this->margins[$border] + $this->paddings[$border];
                    }
                }
            }
        } else {
            $width = (int) $this->width;
            $height = (int) $this->height;
        }
        if(!$width || !$height) {
            $prop = $rdim['height'] / $rdim['width'];

            if(!$width) {
                $width = $height / $prop;
            } else if(!$height) {
                $height = $width * $prop;
            }
        }

        return array(
                "width" => $width,
                "height" => $height
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
    public function getMultiplier($border = false) {
        if(false === $border) return $this->multiplier;
        return $this->multiplier[$border];
    }
}