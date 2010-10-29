<?php
class ImTools extends ImInstance {

    protected $background_file = false;
    protected $is_executed = false;

    public function background($background) {
        if(preg_match("/^#([a-fA-F0-9]{3}){1,2}/", $background)) {

            $this->background_color = $background;

        } else if(file_exists($background)) {

            $im = new ImFile($background, self::$_types);
            $this->background_file = ImFile::getFile($background, self::$_types, self::$_core_settings["_imagecreatefunction"]);

        } else {
            throw new Exception("Unexpected type of background");
        }
        return $this;
    }

    public function writeFile($filename) {
        if(!$this->is_executed){
            $this->execute();
        }
        $path = rtrim($this->dest_dir, "/")."/".$filename;
        echo $path;
    }

    public function execute() {
        


        $this->is_executed = true;
    }
    
}
?>
