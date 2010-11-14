<?php

/**
 * Description of ImagineFilter
 *
 * @author alfonso
 */
class ImagineFilterFilter {
    protected
            $imagine = false,
            $layers = array();

    public function setImagine($imagine) {
        $this->imagine = $imagine;
    }
    public function getImagine() {
        if($this->imagine === false) {
            $this->imagine = Imagine::getInstance();
        }
        return $this->imagine;
    }
    public function addLayer($layer) {
        array_push($this->layers, $layer);
    }
    public function getLayers() {
        return $this->layers;
    }
    public function touch(){
        if(sizeof($this->layers) !== 0){
            foreach($this->getLayers() as $layer){
                $layer->touch();
            }
        }
    }
}
?>
