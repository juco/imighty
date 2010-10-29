<?php
class gdRenderer implements Renderer {
    protected $imagedata = false;
    protected $outputdata = false;
    protected $is_rendered = false;
    protected $render_stack = array();


    public function  __construct() {
        if(false === self::$_core_initialized) {
            self::initCore();
        }
    }

    public function loadFile($filename = "") {
        $types = self::$_types;
        $_imagecreatefunction = self::$_core_settings["_imagecreatefunction"];

        if(!$types || !$_imagecreatefunction) {
            throw new Exception("No se han recibido los tipos de archivo, imagecreatefunction: ".$_imagecreatefunction);
        }

        $configuration = Imagine::getConfiguration();
        if(false !== $configuration->input_dir) {
            $new_filename = rtrim($configuration->input_dir, "/")."/".ltrim($filename, "/");
        }
        if(file_exists($new_filename)) {
            $filename = $new_filename;
        } else {
            if(!file_exists($filename)) {
                throw new Exception("Not a file: ".$filename);
            }
        }

        $info = getimagesize($filename);

        $imagedata = array(
                'width' => $info[0],
                'height' => $info[1],
                'bias' => ($info[0] >= $info[1])?"horizontal":"vertical",
                'aspectratio' => $info[0] / $info[1],
                'type' => $info[2],
                'resource' => null
        );
        if ($types[$imagedata['type']]['supported'] < 1) {
            throw new Exception('Imagetype ('.$types[$imagedata['type']]['ext'].') not supported for reading.');
            return null;
        }
        switch ($imagedata['type']) {
            case 1:
                $dummy = imagecreatefromgif($filename);
                $functionname = $_imagecreatefunction;
                $imagedata['resource'] = $functionname($imagedata['width'], $imagedata['height']);
                imagecopy($imagedata['resource'], $dummy, 0, 0, 0, 0, $imagedata['width'], $imagedata['height']);
                imagedestroy($dummy);
                break;

            case 2:
                $imagedata['resource'] = imagecreatefromjpeg($filename);
                break;

            case 3:
                $dummy = imagecreatefrompng($filename);
                if (imagecolorstotal($dummy) != 0) {
                    $functionname = $_imagecreatefunction;
                    $imagedata['resource'] = $functionname($imagedata['width'], $imagedata['height']);
                    imagecopy($imagedata['resource'], $dummy, 0, 0, 0, 0, $imagedata['width'], $imagedata['height']);
                } else {
                    $imagedata['resource'] = $dummy;
                }
                unset($dummy);
                break;

            default:
                throw new Exception('Imagetype not supported.');
                return null;
        }
        
        $this->imagedata = $imagedata;
        
    }

    public function saveFile($filename) {
        $configuration = Imagine::getConfiguration();
        if(false !== $configuration->output_dir) {
            $new_filename = rtrim($configuration->output_dir, "/")."/".ltrim($filename, "/");
        }
        if(is_dir(dirname($new_filename))) {
            $filename = $new_filename;
        } else {
            if(!is_dir(dirname($filename))) {
                throw new Exception("Not a dir: ".dirname($filename));
            }
        }
        
        $resource = $this->imagedata["resource"];
        //header("Content-Type: image/png;");
        //imagealphablending($resource, false);
        //imagesavealpha($resource, true);
        
        imagepng($resource, $new_filename);
        
    }



    public function addToRenderStack($renderer) {
        array_push($this->render_stack, $renderer);
    }
    public function render() {
        
        foreach($this->render_stack as $renderer){
            $this->mix($renderer);
        }
    }
    public function clearRenderStack(){
        $this->render_stack = array();
    }
    public function mix($renderer){
        $res = $renderer->getResource();

    }

    public function getResource(){
        return $this->imagedata["resource"];
    }

    /*
     * Static
    */

    protected static
            $_core_initialized = false,
            $_core_settings = array();

    protected static $_types = array (
            1 => array (
                            'ext' => 'gif',
                            'mime' => 'image/gif',
                            'supported' => 0
            ),
            2 => array (
                            'ext' => 'jpg',
                            'mime' => 'image/jpeg',
                            'supported' => 0
            ),
            3 => array (
                            'ext' => 'png',
                            'mime' => 'image/png',
                            'supported' => 0
            )
    );

    public static function initCore() {
        $settings = array();
        $settings["gd_info"] = function_exists('gd_info') ? gd_info() : $this->_gd_info();
        preg_match("/\A[\D]*([\d+\.]*)[\D]*\Z/", $settings["gd_info"]['GD Version'], $matches);
        list($settings["_gd_version_string"], $settings["_gd_version_number"]) = $matches;
        $settings["_gd_version"] = substr($settings["_gd_version_number"], 0, strpos($settings["_gd_version_number"], '.'));
        if ($settings["_gd_version"] >= 2) {
            $settings["_imagecreatefunction"] = 'imagecreatetruecolor';
            $settings["_resize_function"] = 'imagecopyresampled';
        } else {
            $settings["_imagecreatefunction"] = 'imagecreate';
            $settings["_resize_function"] = 'imagecopyresized';
        }

        $settings["_gd_ttf"] = $settings["gd_info"]['FreeType Support'];
        $settings["_gd_ps"] = $settings["gd_info"]['T1Lib Support'];
        if ($settings["gd_info"]['GIF Read Support']) {
            self::$_types[1]['supported'] = 1;
            if ($settings["gd_info"]['GIF Create Support']) {
                self::$_types[1]['supported'] = 2;
            }
        }
        $key = 'JPEG Support';
        if (!isset($settings["gd_info"][$key])) {
            $key = 'JPG Support';
        }
        if($settings["gd_info"][$key]) {
            self::$_types[2]['supported'] = 2;
        }
        if ($settings["gd_info"]['PNG Support']) {
            self::$_types[3]['supported'] = 2;
        }
        self::$_core_settings = $settings;
        self::$_core_initialized = true;
    }




}