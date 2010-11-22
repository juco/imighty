<?php
abstract class ImagineBehaviourRenderizable {


    private
            $renderer,
            $render_options = array(),
            $render_stack = array(),
            $configuration = false,
            $imagine = false,
            $filters=array();
    protected
            $is_rendered = false;

    public function  __construct($imagine, $configuration = false) {
        $this->imagine = $imagine;

        if(false !== $configuration) {
            $this->configuration = $configuration;
        } else {
            $this->configuration = $this->imagine->getConfiguration();
        }
        $renderer_class = "ImagineRenderer".ucfirst($this->configuration->renderer);
        $reflection_class = new ReflectionClass($renderer_class);
        $this->renderer = $reflection_class->newInstance($imagine);
        $this->renderer->setConfiguration($this->configuration);
        $this->renderer->setLayer($this);
        $this->configure();
    }
    protected function configure() {
        //do nothing
    }
    public function render() {
        // TODO: filters here add to renderStack Wha
        $this->render_stack = array_reverse($this->render_stack);
        $renderer = $this->renderer();
        $renderer->setRenderOptions($this->getRenderOptions());

        if(false === $this->is_rendered) {
            $renderer->render();
            $name = substr(get_class($renderer), strlen('ImagineRenderer'));
            foreach($this->filters as $filter) {
                $data = call_user_func(array($filter, 'render'.$name), $renderer->pickData());
                $renderer->sendData($data);
            }

            foreach($this->render_stack as $imagine) {
                $imagine->render();
                $renderer->mix($imagine);
            }
        }
        $this->is_rendered = true;
    }

    public function apply($filter) {
        $filter->addLayer($this);
        array_push($this->filters, $filter);
        return $this;
    }
    public function renderer() {
        return $this->renderer;
    }
    public function save($filename) {
        if(false === $this->is_rendered) {
            $this->render();
        }
        $this->renderer()->saveFile($filename);
        return $this;
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
        if($this->hasParent()){
            $this->getParent()->touch();
        }
        $this->renderer()->touch();
        $this->is_rendered = false;
    }
    protected function setRenderOption($option, $value) {
        if(isset($this->render_options[$option])) {
            $this->render_options[$option] = $value;
        } else {
            throw new Exception('Not valid option: '.$option);
        }
    }
    private function getRenderOptions() {
        return $this->render_options;
    }
    protected function configureRenderOption($option, $default = false) {
        $this->render_options[$option] = $default;
    }
}