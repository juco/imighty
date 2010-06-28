<?php
class ImVerse extends ImTools {

    public static function create($options = array()) {
        
        if(!is_array($options)){
            throw new Exception("Settings passed to ::create not an array: ".var_dump($settings, true));
        }
        
        $im = new self($options);
        return $im;
    }   
}