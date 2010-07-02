<?php

class ImagineCore {

    protected static
            $classes = array(),
            $registered = false,
            $registered_tools = array(),
            $imagine_dir = "";

    public static function registerLayers($name, $class) {

        if(!in_array($name, array_keys(self::$registered))) {
            self::$registered_tools[$name] = $class;
        }
    }

    public static function __callStatic($name, $arguments) {

        if(!in_array($name, array_keys(self::$registered_tools))) {
            throw new Exception("Module not loaded: ".$name);
        }
        $argument = sizeof($arguments)?$arguments[0]:null;
        return new self::$registered_tools[$name]($argument);
    }

    public static function registerAutoload($dirs = array()) {

        array_push($dirs, dirname(__FILE__));

        foreach($dirs as $dir) {
            if(!is_dir($dir)) {
                throw new Exception($dir." is not a directory.");
            }
            $files = self::rglob("*.php", $dir);
            $classes = array();
            foreach($files as $file) {
                $classes[substr(basename($file), 0, -4)] = $file;
            }
            self::$classes = array_merge(self::$classes, $classes);
        }

        spl_autoload_register(array("ImagineCore", "autoload"));
    }

    public static function register() {

        if(false !== self::$registered) {
            return;
        }

        $class = get_called_class();

        if(method_exists($class, "configureAutoload")) {
            $dirs = call_user_func($class."::configureAutoload");
        }
        self::registerAutoload($dirs);

        if(method_exists($class, "configureTools")) {

            $tools = call_user_func($class."::configureTools");
            if(is_array($tools)) {

                foreach(array_keys($tools) as $tool_name) {
                    if(method_exists($class, $tool_name)) {
                        throw new Exception($tool_name." tool cannot be defined, reserved name.");
                    }
                }
                self::$registered_tools = $tools;
            }
        }
        self::$registered = true;
    }

    public static function autoload($class) {
        
        if(!isset(self::$classes[$class])) {
            return false;
        }
        if(!is_file(self::$classes[$class])) {
            return false;
        }

        require(self::$classes[$class]);
    }
    public static function rglob($pattern, $path = '', $flags = 0) {
        $paths = glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
        $files = glob($path.$pattern, $flags);
        foreach ($paths as $path) {
            $files = array_merge($files, self::rglob($pattern, $path, $flags));
        }
        return $files;
    }
}