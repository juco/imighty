<?php
abstract class ImagineRendererRenderer {
    public abstract function loadFile($filename);
    public abstract function saveFile($filename);
    public abstract function resize();
    public abstract static function initCore();

    protected
            $configuration,
            $render_options,
            $original_data = false,
            $rendered_data = false,
            $is_rendered = false,
            $layer = false,
            $imagine = false;
    
    protected static 
            $_core_initialized = false;

    public function  __construct($imagine) {
        if(false === self::$_core_initialized) {
            $class = get_called_class();
            call_user_func($class.'::initCore');
        }
        $this->imagine = $imagine;
    }

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
    public function touch(){

        $this->is_rendered = false;
    }
    public function isRendered(){
        return $this->is_rendered;
    }
    public function pickData($name = false) {
        if(!$this->is_rendered) {
            $data =  $this->original_data;
        } else {
            $data = $this->rendered_data;
        }
        if(false !== $name){
            return $data[$name];
        }
        return $data;
    }
    public function getDimmension() {
        $data = $this->pickData();
        return array("width" => $data['width'], 'height' => $data['height']);
    }
    public function getWidth() {
        return $this->pickData("width");
    }
    public function getHeight() {
        return $this->pickData("height");
    }
    public function getResource() {
        return $this->pickData("resource");
    }
    public function sendData($data){
       $this->rendered_data = $data;
       $this->is_rendered = true;
    }
    
    public function render(){
        $dimmension = $this->getRenderOption('dimmension');

        $diff_height = $dimmension['height'] != $this->pickData('height');
        $diff_width = $dimmension['width'] != $this->pickData('width');
        
        $will_resize = $diff_width || $diff_height;
        if($will_resize){
            $this->resize();
        }
    }
    public function getLayer(){
        return $this->layer;
    }
    public function setLayer($layer){
        $this->layer = $layer;
        
    }
}

?>
