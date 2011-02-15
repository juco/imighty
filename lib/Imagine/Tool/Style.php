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
class ImagineToolStyle {
    protected
            $font = 'sans',
            $size = 16,
            $line_height = 20,
            $color = '#fff',
            $background = false,
            $transparent = true,
            $alias = false,
            $align = 'left',
            $maxwidth = false,
            $display = 'inline',
            $font_weight = 'normal',
            $font_style = 'normal',
            $margin_top = 0,
            $margin_bottom = 0;

    protected $touched = array();

    protected $not_inherit = array(
            'display'
    );
    
    public function __construct($elems = array(), $clean = false) {
        if($elems instanceof ImagineToolStyle) {
            $elems = $elems->getTouchedArray();
        }
        if(true === $clean){
            foreach($this->not_inherit as $ni){
                unset($elems[$ni]);
            }
        }
        $this->processArray($elems);
    }
    public function __call($method, $params) {
        if(!sizeof($params)) {
            return $this->$method;
        } else if(sizeof($params) === 1) {
            array_push($this->touched, $method);
            $this->$method = $params[0];
        } else {
            throw new Exception('Too many arguments.');
        }
    }
    public function mix($style) {
        $touched_array = $style->getTouchedArray();

        foreach($this->not_inherit as $ni) {
            $touched_array[$ni] = $style->$ni();
        }
        $this->processArray($touched_array);
    }
    public function getTouchedArray() {
        $out = array();
        foreach ($this->touched as $touched) {
            $out[$touched] = $this->$touched;
        }
        return $out;
    }
    public function processArray($data) {

        foreach($data as $key => $value) {
            if(!property_exists(get_class($this), $key)) {
                throw new Exception('style propertie does not exist: '.$key);
            }
            $this->$key($value);
        }
    }
    public function getFont() {
        $font = $this->font;
        if($this->font_weight == "bold"){
            $font .= '_bold';
        }
        if($this->font_weight == "black"){
            $font .= '_black';
        }
        if($this->font_style == "italic"){
            $font .= '_italic';
        }
        $font = Imagine::getFont($font);
        return $font;

    }
}
?>
