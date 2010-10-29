<?php
interface Renderer {
    public function addToRenderStack($renderer);
    public function render();
    public function loadFile($filename);
    public function saveFile($filename);
    public function clearRenderStack();
}

?>
