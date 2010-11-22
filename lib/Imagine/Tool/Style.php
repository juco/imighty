<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of ImagineToolStyle
 *
 * @author alfonso
 */
class ImagineToolStyle {
    protected
            $font = 'arial',
            $size = 16,
            $line_height = 20,
            $color = '#000',
            $background = '#fff',
            $transparent = true,
            $alias = false,
            $align = 'left',
            $maxwidth = false,
            $display = 'inline',
            $font_weight = 'normal',
            $font_style = 'normal';

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
    public function font($file = false) {
        if(false === $file) {
            return $this->font;
        }
        $font = $this->font;
        if($this->font_weight == "bold"){
            $font .= '_bold';
        }
        if($this->font_style == "italic"){
            $font .= '_italic';
        }
        return Imagine::getFont($font);
    }
}
?>
