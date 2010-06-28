<?php

class ImCore {

    protected static $_error_prefix = "Im: ";

    protected static $_core_settings = array();
    protected static $_core_initialized = false;

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
    protected static $protected_options = array();
    protected static $protected_option_values = array(
        "crop" => array(true, false),
        "align" => array("top", "left", "right", "bottom", "vcenter", "hcenter", "center")
    );

    protected static $settings = array(
            "output_dir" => "",
            "dest_dir" => "",
            "background_color" => "ffffff",
            "width" => false,
            "height" => false,
            "crop" => true,
            "align" => "center"

    );
    public static function set($key, $value) {

        if(!is_array(self::$settings)) {
            throw new Exception("Settings passed to ::set not an array: ".var_dump($settings, true));
        }
        if(key_exists($key, self::$settings)) {
            self::$settings[$key] = $value;
        } else {
            throw new Exception("Unknown settings property: ".$key);
        }
    }
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

    public function getErrorPrefix() {
        return self::$_error_prefix;
    }



}
