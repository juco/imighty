<?php
class ImInstance extends ImCore {

    protected $options = array();
 
    public function __construct($options) {
        if(false === self::$_core_initialized) {
            self::initCore();
        }
        $this->options = self::$settings;

        foreach($options as $key => $value) {
            $this->$key = $value;
        }

    }

    public function  __set($name,  $value) {
        if(!key_exists($name, $this->options)) {
            throw new Exception ("set Unknown option: ".$name);
        }
        if(isset(self::$protected_option_values[$name])){
            $values = self::$protected_option_values[$name];
            if(!in_array($value, $values)){
                throw new Exception("Valor no permitido: ".$name." => ".$value.", sólo permitidos: ".implode(", ", $values));
            }
        }
        $this->options[$name] = $value;
    }
    public function __get($name) {
        if(!key_exists($name, $this->options)) {
            throw new Exception("get Unknown option: ". $name);
        }

        if(in_array($name, self::$protected_options)) {
            throw new Exception("protected option: ".$name);
        }
        return $this->options[$name];
    }

    public function __call($name,  $arguments) {
        
        $property = $this->decamelize($name);

        if(key_exists($property, $this->options)) {
            
            if(sizeof($arguments) == 0) {

                return $this->$property;

            } else if (sizeof($arguments) == 1) {

                $this->$property = $arguments[0];
                return $this;
                
            } else {

                throw new Exception("Only one argument for this setter: ".$name);
            }
        } else {
            throw new Exception("Property not found: ". $property);
        }
    }








    protected function camelize($text) {
        return preg_replace(array('#/(.?)#e', '/(^|_|-)+(.)/e'), array("'::'.strtoupper('\\1')", "strtoupper('\\2')"), $text);
    }

    protected function decamelize($str) {
        $tokens = preg_split('/([A-Z])/', $str, null, (PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE));
        $result = array();
        $len = count($tokens);
        if ($len == 1) {
            return $str;
        }
        $result[] = $tokens[0];
        for ($i = 1; $i < $len; $i += 2) {
            $r = strtolower($tokens[$i]);
            if (isset($tokens[$i+1])) {
                $r .= $tokens[$i+1];
            }
            $result[] = $r;
        }
        return implode('_', $result);
    }
}