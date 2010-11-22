<?php

/**
 * Description of ImagineRendererGdText
 *
 * @author alfonso
 */
class ImagineRendererGdText {

    protected
            $renderer = false;

    protected
            $test_chars = 'abcdefghijklmnopqrstuvwxyzáéíóúñçABCDEFGHIJKLMNOPQRSTUVWXYZÁÉÍÓÚÑÇ1234567890!@#$%^&*()\'"\\/;.,`~<>[]{}-+_-=';

    public function __construct(ImagineRendererGd $renderer) {
        $this->renderer = $renderer;
        $this->exp = 8;
    }

    public function write($blocks) {
        
        $layer_width = $this->getRenderer()->getLayer()->width();
        $blocks = $this->processBlocks($blocks, $layer_width); //by reference

       $last_chunk = $blocks[sizeof($blocks)-1];

        $y = $last_chunk['position']['y'];
        $real_height =  $y + $last_chunk['style']->line_height();

        $im = imagecreate ($layer_width*$this->exp, $real_height*$this->exp);



        foreach ($blocks as $block) {
            $style = $block['style'];

            $color = $this->hex_to_rgb($style->color());
            $background = $this->hex_to_rgb($style->background());
            $background_alloc = imagecolorallocate($im,$background['r'],$background['g'],$background['b']);
            $x = $block['position']['x']*$this->exp;
            $y = $block['position']['y']*$this->exp;
            imagefilledrectangle($im, $x, $y, $x + $block['width']  * $this->exp, $y + $style->line_height() * $this->exp, $background_alloc);
            $color_alloc = imagecolorallocate($im,$color['r'],$color['g'],$color['b']);
            imagettftext(
                    $im,
                    $block['style']->size()*72/96*$this->exp,
                    0,
                    $block['position']['x'] * $this->exp,
                    $this->yToLine($block['position']['y']*$this->exp, $block['style']->line_height()*$this->exp, $block['style']->size()*$this->exp),
                    $color_alloc,
                    $block['style']->font(true),
                    $block['text']
            );

        }
        $im2 = imagecreatetruecolor($layer_width, $real_height);
        imagecopyresampled($im2, $im, 0, 0, 0, 0, $layer_width, $real_height, $layer_width*$this->exp, $real_height*$this->exp);
        $this->renderer->sendData(array(
                'width' => $layer_width,
                'height' => $real_height,
                'resource' => $im2
        ));

    }
    protected function processBlocks($blocks, &$layer_width, &$x = 0, &$y = 0) {
        // baseline = .347 * line_height + line_center
        $output = array();
        $real_width = 0;
        $real_height = 0;
        foreach($blocks as $which => $block) {

                $style = $block['style'];
            if(!is_array($block['value'])) {

                $value = $block['value'];
                if($style->maxwidth()) {
                    $max_width = $style->maxwidth();
                } else {
                    $max_width = $layer_width;
                }

                if(!$max_width) {
                    $metrix = $this->getMetrics($block['value'], $style);
                    $output[] = array(
                            'text' => $block['value'],
                            'position' => array('x' => $x, 'y' => $y),
                            'style' => $style,
                            'size' => $metrix
                    );
                    if($real_height < $y + $metrix['height']) {
                        $real_height = $y + $metrix['height'];
                    }
                    if($real_width < $x + $metrix['width']) {
                        $real_width = $x + $metrix['width'];
                    }

                    if($style->display() != "block") {
                        $x += $metrix['width'];
                    }
                } else {
                    $output = array_merge($output, $this->lineSplit($block['value'], $max_width, $style, $x, $y));
                }

            } else {

                $output = array_merge($output, $this->processBlocks($block['value'], $layer_width, $x, $y));

            }
            if($style->display() == "block" && !is_int($which)) {
                $y += $style->line_height();
                $x = 0;

            }
        }
        if($real_width > $layer_width) {
            $layer_width = $real_width;
        }
        return $output;
    }
    protected function yToLine($y, $line_height, $size) {
        return round($y + $line_height / 2 + $size / 3);
    }

    protected function lineSplit($line, $max_width, $style, &$x, &$y) {
        $line = str_replace('\n', ' ', $line);
        $line = preg_replace('/\s{2,}/',' ', $line);

        $space_metrics = $this->getMetrics(' ', $style);
        $chunks = explode(' ', $line);
        $output = array(array(
                        'text' => '',
                        'position' => array('x' => $x, 'y' => $y),
                        'style' => $style
        ));
        $actual_count = 0;
        foreach($chunks as $i => $chunk) {
                if($i != 0){
                    $line_part = $output[$actual_count]['text'].' '.$chunk;
                } else {
                    $line_part = $chunk;
                }
                $line_metrics = $this->getMetrics($line_part, $style);
                $line_width = $line_metrics['width'];
                if($line_width + $x > $max_width) {
                    $chunk_metrics = $this->getMetrics($chunk, $style);
                    $y += $style->line_height();
                    $x = 0;
                    
                    $output[++$actual_count] = array(
                            'text' => $chunk,
                            'position' => array('x' => $x, 'y' => $y),
                            'style' => $style,
                            'width' => $chunk_metrics['width']
                    );
                } else {
                    
                    $output[$actual_count]['text'] = $line_part;
                    $output[$actual_count]['width'] = $line_width;
                }
        }
        $x = $line_width + $x;
        return $output;
    }
    public function getMetrics($text, $style) {
        $box = imagettfbbox($style->size()*72/96*$this->exp, 0, $style->font(true), $text);
        $width = $box[0]/$this->exp + $box[2]/$this->exp;

        $height = $box[7]/$this->exp - $box[1]/$this->exp;
        return array('width' => $width, 'height' => $height);
    }

  
    public function hex_to_rgb($hex) {
        ## Convert HEX to RGB 255,255,255
        ## remove '#'
        $hex = str_replace('#','',$hex);
        # expand short form ('fff') color
        if(strlen($hex) == 3)
            $hex .= $hex;
        if(strlen($hex) != 6)
            $hex == '000000';
        ## convert
        $rgb['r'] = hexdec(substr($hex,0,2)) ;
        $rgb['g'] = hexdec(substr($hex,2,2)) ;
        $rgb['b'] = hexdec(substr($hex,4,2)) ;
        return $rgb ;
    }

    public function getRenderer() {
        return $this->renderer;
    }
}
?>
