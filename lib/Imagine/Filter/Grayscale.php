<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of ImagineFilterGrayscale
 *
 * @author alfonso
 */
class ImagineFilterGrayscale  extends ImagineFilterFilter {
    //put your code here

    protected
            $white = "#ffffff",
            $black = "#000000",
            $colorize = false;

    public function getWhite() {
        return $this->white;
    }
    public function getBlack() {
        return $this->black;
    }
    public function getColor($color) {
        if($color === false) {
            $this->colorize = true;
        }
        return $this->color;
    }
    public function white($white) {
        $this->touch();
        $this->white = $white;
        return $this;
    }
    public function black($black) {
        $this->touch();
        $this->black;
        return $this;
    }
    public function color($color) {

        $this->touch();
        $this->color = $color;
        return $this;
    }
    protected function proccessColor($c) {
        if(strlen($c) == 3) {
            $c = $c[0].$c[0].$c[1].$c[1].$c[2].$c[2];
        }
        preg_match("/(.{2})(.{2})(.{2})/", $c, $matches);
        list($rgb, $r, $g, $b) = $matches;
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array($r, $g, $b);
    }
    public function renderGd($data) {
        $output = array(
                'width' => $data['width'],
                'height' => $data['height'],
                'resource' => imagecreatetruecolor($data['width'], $data['height'])
        );
        imagecopy($output['resource'], $data['resource'], 0, 0, 0, 0, $data['width'], $data['height']);
        imagefilter($output['resource'], IMG_FILTER_GRAYSCALE);
        if($this->color) {
            $params = array_merge(array($output['resource'], IMG_FILTER_COLORIZE), $this->proccessColor($this->color));
            call_user_func_array('imagefilter', $params);
        }

        return $output;
    }
}
?>
