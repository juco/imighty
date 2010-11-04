<?php
abstract class Renderer {
    public abstract function getDimmension();
    public abstract function setDimmension($dimmension);
    public abstract function addToRenderStack($renderer);
    public abstract function render();
    public abstract function loadFile($filename);
    public abstract function saveFile($filename);
    public abstract function clearRenderStack();
    
    protected $configuration;
    public function setConfiguration($configuration){
        $this->configuration = $configuration;
    }
    public function getConfiguration(){
        return $this->configuration;
    }
}

?>
