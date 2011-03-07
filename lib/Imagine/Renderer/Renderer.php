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
 * The base renderer class of Imagine
 *
 * @package     Imagine
 * @author      Alfonso de la Osa <alfonso.delaosa@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        https://github.com/botverse/imagine
 */
abstract class ImagineRendererRenderer {

    public abstract function loadFile($filename = "");

    public abstract function saveFile($filename);

    public abstract function resize();

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

    public function __construct($imagine) {
        if (false === self::$_core_initialized) {
            call_user_func(get_class($this).'::initCore');
        }
        $this->imagine = $imagine;
    }

    public function setConfiguration($configuration) {
        $this->configuration = $configuration;
    }

    public function getConfiguration() {
        return $this->configuration;
    }

    public function setRenderOptions($options) {

        $this->render_options = $options;
    }

    public function getRenderOption($option) {
        if (isset($this->render_options[$option])) {
            return $this->render_options[$option];
        } else {
            throw new Exception('Not a present option: ' . $option);
        }
    }

    public function touch() {
        $this->is_rendered = false;
    }

    public function isRendered() {
        return $this->is_rendered;
    }
    public function isLoaded(){
        return $this->original_data !== false;
    }

    public function pickData($name = false) {
        if (!$this->is_rendered) {
            $data = $this->original_data;
        } else {
            $data = $this->rendered_data;
        }
        if (false !== $name) {
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

    public function sendData($data) {
        if($data['width']==null){
            throw new Exception();
        }
        $this->rendered_data = $data;
        $this->is_rendered = true;
    }

    public function render() {
        $dimmension = $this->getRenderOption('dimmension');
        
        if (false === $this->original_data && false === $this->is_rendered) {
            $this->createResource($dimmension);
        } else {
            $diff_height = $dimmension['height'] != $this->pickData('height');
            $diff_width = $dimmension['width'] != $this->pickData('width');

            $will_resize = $diff_width || $diff_height;
            if ($will_resize) {
                $this->resize();
            }
        }
    }

    public function getLayer() {
        return $this->layer;
    }

    public function setLayer($layer) {
        $this->layer = $layer;
    }

}

?>
