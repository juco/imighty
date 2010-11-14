<?php
header("Content-type: image/png");
//$n = utf8_encode(urldecode("%E1fs%E9enU%C6%D1%F1"));
//echo $n;
//$n = hexdec("f1");
//$ae = dechex(252);
//echo "\n".$ae;
//echo "\n".utf8_encode(urldecode("%".$ae));
$num_chars = 1024;
$height = ($num_chars+2)*15;
$im = imagecreate (350, $height);
$color = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 350, $height, $color);
$color = imagecolorallocate($im, 0, 0, 0);
$i = 0;
while ($i<1024){
	$char = chr($i);
	$hex = "%".sprintf("%s",dechex($i));
	imagettftext($im,10,0,0,15*$i,$color,"fonts/GothamBook.ttf","dec: $i hex: $hex ".$char);
	
	$ii = $i + 1024;
	$char2 = chr($ii);
	$hex2 = "%".sprintf("%s",dechex($ii));
	imagettftext($im,10,0,175,15*$i,$color,"fonts/GothamBook.ttf","dec: $ii hex: $hex2 ".$char2);
	
	$i++;
}

imagepng($im);
?>