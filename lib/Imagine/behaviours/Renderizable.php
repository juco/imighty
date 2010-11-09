<?php
abstract class Renderizable  {
    
    
    private
            $renderer,
            $render_options = array(),
            $render_stack = array();
    protected 
            $is_rendered = false;

    public function  __construct($imagine) {
        $this->imagine = $imagine;
        $renderer_class = $this->imagine->getConfiguration()->renderer;
        $reflection_class = new ReflectionClass($renderer_class);
        $this->renderer = $reflection_class->newInstance($imagine);
        $this->configure();
    }
    protected function configure(){
        //do nothing
    }
    public function render() {
        // TODO: filters here add to renderStack Wha
        $this->getRenderer()->setRenderOptions($this->getRenderOptions());
        
        if(false === $this->is_rendered) {

            $this->getRenderer()->resize();
            foreach($this->render_stack as $imagine) {
                $imagine->render();
                $this->getRenderer()->mix($imagine);
            }


        }
        $this->is_rendered = true;
    }

    public function getRenderer() {
        return $this->renderer;
    }
    public function saveFile($filename) {
        if(false === $this->is_rendered) {
            $this->render();
        }
        $this->getRenderer()->saveFile($filename);
    }
    public function addToRenderStack($renderer) {
        array_push($this->render_stack, $renderer);
    }
    public function clearRenderStack() {
        $this->render_stack = array();
    }
    public function isRendered() {
        return $this->is_rendered;
    }
    public function touch() {
        $this->is_rendered = false;
    }
    protected function setRenderOption($option, $value){
        if(isset($this->render_options[$option])){
            $this->render_options[$option] = $value;
        } else {
            throw new Exception('Not valid option: '.$option);
        }
    }
    private function getRenderOptions(){
        return $this->render_options;
    }
    protected function configureRenderOption($option, $default = false){
            $this->render_options[$option] = $default;
    }
}