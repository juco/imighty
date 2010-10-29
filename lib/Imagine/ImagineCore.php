<?php

class ImagineCore {

    protected static
            $classes = array(),
            $registered = false,
            $registered_tools = array(),
            $imagine_dir = "";

    private static $configuration = false;

    

    public static function registerLayers($name, $class) {

        if(!in_array($name, array_keys(self::$registered))) {
            self::$registered_tools[$name] = $class;
        }
    }

    public static function __callStatic($name, $arguments) {

        if(!in_array($name, array_keys(self::$registered_tools))) {
            throw new Exception("Module not loaded: ".$name);
        }

        $reflection = new ReflectionClass(self::$registered_tools[$name]);
        if(0 === sizeof($arguments)){
            $arguments = null;
        }
        return $reflection->newInstance($arguments);
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
    public static function isRegistered() {
        return (false !== self::$registered);
    }
    public function register($configuration = false) {
        if(false !== self::$registered) {
            return;
        }

        if(false !== $configuration) {
            
            if(get_class($configuration) == "ImagineConfiguration" || is_subclass_of($configuration, "ImagineConfiguration")) {
                self::$configuration = $configuration;
            } else {
                throw new Exception("Wrong configuration passed.");
            }
        } else {
            self::$configuration = new ImagineConfiguration();
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
    public static function getConfiguration(){
        return self::$configuration;
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