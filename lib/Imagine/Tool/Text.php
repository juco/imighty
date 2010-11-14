<?php
class ImagineToolText extends ImagineBehaviourContainer {
    protected
            $styles = array(),
            $text = '';

    public function style() {
        $this->touch();
        $args = func_get_args();
        if(sizeof($args) == 2 && is_array($args[1])) {
            $this->styles[$args[0]] = new ImagineToolStyle($args[1]);
        } else if(sizeof($args)) {
            $this->styles = array_merge($this->styles, $args);
        } else {
            return new ImagineToolStyle();
        }
        return $this;
    }
    public function write($text) {
        $this->touch();
        $this->text = $text;
        return $this;
    }
    public function render() {
        if($this->is_rendered === false) {

            $this->renderer()->text()->write($this->processText());
        }
        parent::render();

    }
    public function processText() {
        
        $blocks = $this->getHtmlDom($this->text);
        foreach ($blocks as $i => $block) {
            
        }
    }
    public function getHtmlDom() {

        Imagine::includeVendor('phpQuery.php');

        if(!preg_match('/^\s*</', $this->text)) {
            $this->text = '<p>'.$this->text.'</p>';
        }

        $html = phpQuery::newDocument($this->text);
        $blocksdom = $html->children();
        $blocks = array();

        for($i = 0; $i < $blocksdom->count(); $i++) {
            $block =  $blocksdom->eq($i);
            $blocknode = $blocksdom->get($i);

            $blocks[] = array(
                    'style_chain' => 'all '.$blocknode->nodeName,
                    'content' => $block->text()
            );
        }
        return $blocks;
    }
}