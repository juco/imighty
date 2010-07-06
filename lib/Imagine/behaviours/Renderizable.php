<?php
class Renderizable {
    private
            $renderer,
            $is_rendered = false;

    public function  __construct() {
        $renderer_class = Imagine::getConfiguration()->renderer;
        $reflection_class = new ReflectionClass($renderer_class);
        $this->renderer = $reflection_class->newInstance();
    }
    public function render() {
        // TODO: filters here add to renderStack Wha

        $this->is_rendered = true;
        
        return $this->getRenderer()->render();
    }
    public function getRenderer() {
        return $this->renderer;
    }
    public function saveFile($filename) {
        if(false === $this->is_rendered){
            $this->render();
        }
        $this->getRenderer()->saveFile($filename);
    }
    public function addToRenderStack($renderer){
        $this->getRenderer()->addToRenderStack($renderer);
    }
    public function clearRenderStack(){
        $this->getRenderer()->clearRenderStack();
    }
    public function isRendered(){
        return $this->is_rendered;
    }
    public function touch(){
        $this->is_rendered = false;
    }
}