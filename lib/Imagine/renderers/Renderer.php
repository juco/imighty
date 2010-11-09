<?php
abstract class Renderer {
    public abstract function loadFile($filename);
    public abstract function saveFile($filename);
    
    protected
            $configuration,
            $render_options;
    public function setConfiguration($configuration){
        $this->configuration = $configuration;
    }
    public function getConfiguration(){
        return $this->configuration;
    }
    public function setRenderOptions($options){
        
        $this->render_options = $options;
    }
    public function getRenderOption($option){
        if(isset($this->render_options[$option])){
            return $this->render_options[$option];
        } else {
            throw new Exception('Not a present option: '.$option);
        }
    }
}

?>
