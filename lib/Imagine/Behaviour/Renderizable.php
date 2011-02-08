<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * http://www.opensource.org/licenses/lgpl-license.php.
 */

/**
 *
 * @package     Imagine
 * @author      Alfonso de la Osa <alfonso.delaosa@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        https://github.com/botverse/imagine
 */
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
        $renderer = $this->getRenderer();
        $renderer->setRenderOptions($this->getRenderOptions());

        if(false === $this->is_rendered) {
            if(!$renderer->isRendered()){
                $renderer->render();
            }
            
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
    public function getRenderer() {
        return $this->renderer;
    }
    public function save($filename) {
        if(false === $this->is_rendered) {
            $this->render();
        }
        $this->getRenderer()->saveFile($filename);
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
        $this->getRenderer()->touch();
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
    public function getRenderOption($option){
        if(!isset($this->render_options[$option])){
            throw new Exception('This renderizable has no render option: '.$option);
        }
        return $this->render_options[$option];
    }
    protected function configureRenderOption($option, $default = false) {
        $this->render_options[$option] = $default;
    }
}