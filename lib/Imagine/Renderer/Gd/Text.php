<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of Text
 *
 * @author alfonso
 */
class ImagineRendererGdText extends ImagineRendererGdTextRenderer {
    public function write($blocks) {
        $layer_width = $this->getLayerWidth();

        $blocks = $this->processBlocks($blocks, $layer_width); // $x and $y by reference = 0
        $this->render($blocks, $layer_width);

    }
    protected function getLayerWidth(){
        $layer = $this->getRenderer()->getLayer();
        if($layer->width() == '100%'){
            $dimmension = $layer->getParent()->getDimmension();
            $width = $dimmension['width'] - $layer->margin('left') - $layer->margin('right') - $layer->padding('left') - $layer->padding('right');
        } else {
            $width = $layer->width();
        }
        
        return $width;
    }
    protected function processBlocks($blocks, &$layer_width, &$x = 0, &$y = 0) {
        // 
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
                            'width' => $metrix['width']
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

    protected function lineSplit($line, $max_width, $style, &$x, &$y) {
        $line = str_replace('\n', ' ', $line);
        $line = preg_replace('/\s{2,}/',' ', $line);

        $space_metrics = $this->getMetrics(' ', $style);
        $chunks = explode(' ', $line);
        $output = array(array(
                        'text' => '',
                        'position' => array('x' => $x, 'y' => $y),
                        'style' => $style,
                        'width' => 0
        ));
        $actual_count = 0;
        foreach($chunks as $i => $chunk) {
            if($i != 0) {
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

}
?>
