<?php

/**
 * Description of ImagineRendererGdText
 *
 * @author alfonso
 */
class ImagineRendererGdText {

    protected 
            $renderer = false;
    
    public function __construct(ImagineRendererGd $renderer){
        $this->renderer = $renderer;
    }
    
    public function write($blocks) {
//        $output = array();
//        $gdfont["text"] = str_replace("\r\n", "\n",$gdfont["text"]);
//        $gdfont["text"] = str_replace("\r", "",$gdfont["text"]);
//        $blocks = explode("\n", $gdfont["text"]);
//        foreach($blocks as $block) {
//            $block_output = array();
//            ## Determine baseline and base image height
//            $box = imagettfbbox($gdfont['size'],0,$gdfont['font_dir'].$gdfont['font'],$block);
//            $width = abs($box[0]) + abs($box[2]);
//
//            $box = @imagettfbbox($gdfont['size'],0,$gdfont['font_dir'].$gdfont['font'],$gdfont['test_chars']);
//            $dip = abs($box[3]);
//            $box = imagettfbbox($gdfont['size'],0,$gdfont['font_dir'].$gdfont['font'],$block);
//            $lowheight = abs($box[5]-$dip);
//            $height = abs($box[5]);
//            //$gdfont["text"] = str_replace("", "", $gdfont["text"]);
//            ## Check for multiple lines, place each newline into array
//            if (!$gdfont['leading'])
//                $gdfont['leading'] = round($lowheight*.2);
//            if ($gdfont['max_width']) {
//
//                while ($width > ($gdfont['max_width']-($gdfont['padding']*2))) {
//
//                    $lines++;
//                    $i = $width;
//                    $t = strlen($block);
//
//                    while (($i > ($gdfont['max_width']-($gdfont['padding']*2))) ) {
//                        //&& $gdfont["text"][$t]!="\r"
//
//                        $t--;
//                        $box = imagettfbbox($gdfont['size'],0,$gdfont['font_dir'].$gdfont['font'],substr($block,0,$t));
//                        $i = abs($box[0]) + abs($box[2]);
//                    }
//
//                    $t = strrpos(substr($block, 0, $t),' ');
//                    $block_output[$lines-1] = substr($block,0,$t);
//
//                    $block = ltrim(substr($block, $t));
//                    $block_output[] = $block;
//
//                    $box = imagettfbbox($gdfont['size'],0,$gdfont['font_dir'].$gdfont['font'],$block_output[$lines]);
//                    $width = abs($box[0]) + abs($box[2]);
//                }
//            } else {
//                $gdfont['max_width'] = $width;
//                $hpad = ($gdfont['padding']*2);
//            }
//
//            $lines++;
//
//            if (!count($block_output))
//                $output[] = $block;
//            else
//                $output += $block_output;
//        }
//        ## Create total image size
//        $amp_width = $gdfont['max_width']+$gdfont['hadj']+$hpad;
//        $amp_height = ($lowheight*$lines) + ($gdfont['leading']*($lines-1)) + $gdfont['vadj'] + ($gdfont['padding']*2);
//        $im = imagecreate ($amp_width, $amp_height);
//        $im2 = imagecreatetruecolor(floor($amp_width / $gdfont["multiplyer"]), floor($amp_height / $gdfont["multiplyer"]));
//        ## Color and Background Color
//        $color = hex_to_rgb($gdfont['color']);
//        $background = hex_to_rgb($gdfont['background']);
//        $color1 = imagecolorallocate($im,$background['r'],$background['g'],$background['b']);
//        $color12 = imagecolorallocate($im2,$background['r'],$background['g'],$background['b']); ## Sets background color
//        $color2 = imagecolorallocate($im,$color['r'],$color['g'],$color['b']);
//        $color22 = imagecolorallocate($im,$color['r'],$color['g'],$color['b']);
//
//        ## Transparency and Alias
//        if ($gdfont['transparent']) {
//            imagecolortransparent($im,$color1);
//            imagecolortransparent($im2,$color1);
//        }
//        if ($gdfont['alias'])
//            $color2 = -$color2;
//
//        ## Output all the line of text as placed in array, configure alignment and padding
//        $i = 2;
//        $vpad = $gdfont['padding'];
//        foreach ($output as $value) {
//            $box = imagettfbbox($gdfont['size'],0,$gdfont['font_dir'].$gdfont['font'],$value);
//            $w = abs($box[0]) + abs($box[2]);
//            if ($gdfont['alignment'] == 'right')
//                $x =  ($gdfont['max_width']-($gdfont['padding']*2))-$w;
//            if ($gdfont['alignment'] == 'center')
//                $x = (($gdfont['max_width']-($gdfont['padding']*2))-$w) / 2;
//
//            imagettftext($im,$gdfont['size'],0,$x+$gdfont['padding'],($height*($i-1))+($gdfont['leading']*($i-2))+$vpad,$color2,$gdfont['font_dir'].$gdfont['font'],$value);
//            $i++;
//        }
//        imageantialias($im2, true);
//        imagecopyresampled($im2, $im, 0, 0, 0, 0, floor($amp_width / $gdfont["multiplyer"]), floor($amp_height / $gdfont["multiplyer"]), $amp_width, $amp_height);
//
//        if($gdfont['image_type'] == 'png') {
//            if($cache)
//                imagepng($im2,$gdfont['cache_path'].$filename.'.png');
//            else
//                imagepng($im2);
//        }
//        if($gdfont['image_type'] == 'gif') {
//            if($cache)
//                imagegif($im2,$gdfont['cache_path'].$filename.'.gif');
//            else
//                imagegif($im2);
//        }
//        imagedestroy($im);
//        imagedestroy($im2);

    }
}
?>
