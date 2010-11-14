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
            $selector = 'all';

    public function __call($method, $params){
        if(!sizeof($params)){
            return $this->$method;
        } else if(sizeof($params) === 1){
            $this->$method = $params;
        } else {
            throw new Exception('Too many arguments.');
        }
    }

}
?>
