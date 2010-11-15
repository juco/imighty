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
            $size = 11,
            $color = '#666633',
            $background = '#CCCC99',
            $transparent = true,
            $alias = false,
            $alignment = 'center',
            $leading = 10,
            $padding = 20,
            $vadj = 0,
            $hadj = 0,
            $maxwidth = 400,
            $display = 'block',
            $selector = 'all',
            $font_weight = 'normal';
    protected $touched = array();
    public function __construct($elems = array()){
        $this->processArray($elems);
    }
    public function __call($method, $params){
        if(!sizeof($params)){
            return $this->$method;
        } else if(sizeof($params) === 1){
            array_push($this->touched, $method);
            $this->$method = $params[0];
        } else {
            throw new Exception('Too many arguments.');
        }
    }
    public function mix($style){
        $this->precessArray($style->getTouchedArray());
    }
    public function getTouchedArray(){
        $out = array();
        foreach ($this->touched as $touched){
            $out[$touched] = $this->$touched;
        }
        return $out;
    }
    public function processArray($data){

        foreach($data as $key => $value){
            if(!property_exists(get_class($this), $key)){
                throw new Exception('style propertie does not exist: '.$key);
            }
            $this->$key($value);
        }
    }
}
?>
