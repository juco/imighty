<?php

class ImagineCore {
    protected static
            $classes = array(),
            $registered_autoload_dirs = array();

    protected
            $registered = false,
            $registered_tools = array(),
            $imagine_dir = "";

    private $configuration = false;

    public static function getInstance($configuration = false) {
        $class = get_called_class();

        $imagine = new $class();
        $imagine->register($configuration);
        return $imagine;
    }

    public function registerLayers($name, $class) {

        if(!in_array($name, array_keys($this->registered))) {
            $this->registered_tools[$name] = $class;
        }
    }

    public function __call($name, $arguments) {

        if(!in_array($name, array_keys($this->registered_tools))) {
            throw new Exception("Module not loaded: ".$name);
        }

        $reflection = new ReflectionClass($this->registered_tools[$name]);

        array_unshift($arguments, $this);
        
        return $reflection->newInstanceArgs($arguments);
    }

    public function registerAutoload($dirs = array()) {

        if(!sizeof(self::$registered_autoload_dirs)){
            spl_autoload_register(array("ImagineCore", "autoload"));
        }
        array_push($dirs, dirname(__FILE__));

        foreach($dirs as $dir) {
            if(!in_array($dir, self::$registered_autoload_dirs)) {

                if(!is_dir($dir)) {
                    throw new Exception($dir." is not a directory.");
                }

                $files = self::rglob("*.php", $dir);
                $classes = array();
                foreach($files as $file) {
                    $classes[substr(basename($file), 0, -4)] = $file;
                }
                self::$classes = array_merge(self::$classes, $classes);
                array_push(self::$registered_autoload_dirs, $dir);
            }
        }

    }
    public function isRegistered() {
        return (true === $this->registered);
    }
    public function register($configuration = false) {
        if(false !== $this->registered) {
            throw new Exception("Already registered.");
        }

        if(false !== $configuration) {

            if(get_class($configuration) == "ImagineConfiguration" || is_subclass_of($configuration, "ImagineConfiguration")) {
                $this->configuration = $configuration;
            } else {
                throw new Exception("Wrong configuration passed.");
            }
        } else {
            $this->configuration = new ImagineConfiguration();
        }


        $class = get_called_class();

        if(method_exists($class, "configureAutoload")) {
            $dirs = call_user_func($class."::configureAutoload");
        }
       $this->registerAutoload($dirs);

        if(method_exists($class, "configureTools")) {

            $tools = call_user_func($class."::configureTools");
            if(is_array($tools)) {

                foreach(array_keys($tools) as $tool_name) {
                    if(method_exists($class, $tool_name)) {
                        throw new Exception($tool_name." tool cannot be defined, reserved name.");
                    }
                }
                $this->registered_tools = $tools;
            }
        }
        $this->registered = true;
    }
    public function getConfiguration() {
        return $this->configuration;
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
    public static function dump($data){
        if(is_array($data) || is_object($data)){
            var_dump($data);
        }
    }
}