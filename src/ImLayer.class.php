<?php

class ImLayer extends Appendable{
    private static $registered = array(
        "layer" => "ImLayer",
        "image" => "ImImage",
        "text" => "ImText"
    );
    public static function register($name, $class) {
        if(!in_array($name, array_keys(self::$registered))){
            self::$registered[$name] = $class;
        }
    }
    public static function __callStatic($name, $arguments){
        if(!in_array($name, array_keys(self::$registered))) {
            throw new Exception("Module not loaded: ".$name);
        }
        $argument = sizeof($arguments)?$arguments[0]:null;
        return new self::$registered[$name]($argument);
    }
}