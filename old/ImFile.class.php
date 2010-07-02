<?php
class ImFile{
   
    public static function getFile($filename = "", $types = false, $_imagecreatefunction = false) {
        if(!$types || !$_imagecreatefunction){
            throw new Exception("No se han recibido los tipos de archivo, imagecreatefunction: ".$_imagecreatefunction);
        }
        if (file_exists($filename)) {
            $info = getimagesize($filename);

            $filedata['width'] = $info[0];
            $filedata['height'] = $info[1];
            $filedata['bias'] = ($filedata['width'] >= $filedata['height'])?"horizontal":"vertical";
            $filedata['aspectratio'] = $filedata['width'] / $filedata['height'];
            $filedata['type'] = $info[2];
            $filedata['resource'] = null;
            if ($types[$filedata['type']]['supported'] < 1) {
                trigger_error(self::$_error_prefix . 'Imagetype ('.$types[$filedata['type']]['ext'].') not supported for reading.', E_USER_ERROR);
                return null;
            }
            switch ($filedata['type']) {
                case 1:
                    $dummy = imagecreatefromgif($filename);
                    $functionname = $_imagecreatefunction;
                    $filedata['resource'] = $functionname($filedata['width'], $filedata['height']);
                    imagecopy($filedata['resource'], $dummy, 0, 0, 0, 0, $filedata['width'], $filedata['height']);
                    imagedestroy($dummy);
                    break;

                case 2:
                    $filedata['resource'] = imagecreatefromjpeg($filename);
                    break;

                case 3:
                    $dummy = imagecreatefrompng($filename);
                    if (imagecolorstotal($dummy) != 0) {
                        $functionname = $_imagecreatefunction;
                        $filedata['resource'] = $functionname($filedata['width'], $filedata['height']);
                        imagecopy($filedata['resource'], $dummy, 0, 0, 0, 0, $filedata['width'], $filedata['height']);
                    } else {
                        $filedata['resource'] = $dummy;
                    }
                    unset($dummy);
                    break;

                default:
                    trigger_error(self::$_error_prefix . 'Imagetype not supported.', E_USER_ERROR);
                    return null;
            }
            return $filedata;
        } else {
            trigger_error(self::$_error_prefix . 'Imagefile (' . $filename . ') does not exist.', E_USER_ERROR);
            return null;
        }
    }
    public function saveFile($filename, $image) {
        
    }
}