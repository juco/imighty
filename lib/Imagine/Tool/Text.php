<?php

class ImagineToolText extends ImagineBehaviourContainer {

    protected
    $styles = array(),
    $text = '';

    public function style() {
        $this->touch();
        $args = func_get_args();
        if (sizeof($args) == 2 && is_array($args[1])) {
            $this->styles[$args[0]] = new ImagineToolStyle($args[1]);
        } else if (sizeof($args)) {
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
        if ($this->is_rendered === false) {

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
        $text = $this->text;
        if (!preg_match('/^\s*<root/', $text)) {
            $text = '<root>' . $text . '</root>';
        }

        $html = phpQuery::newDocument($this->text);
        $blocksdom = $html->children();
        $blocks = array();

        for ($i = 0; $i < $blocksdom->count(); $i++) {
            $block = $blocksdom->eq($i);
            $blocknode = $blocksdom->get($i);

            $blocks[] = array(
                'style_chain' => 'all ' . $blocknode->nodeName,
                'content' => $block->text()
            );
        }
        return $blocks;
    }

    protected function toArray($data) {
        /* mvo voncken@mailandnews.com
          original ripped from  on the php-manual:gdemartini@bol.com.br
          to be used for data retrieval(result-structure is Data oriented) */
        $p = xml_parser_create();
        xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
        xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
        xml_parse_into_struct($p, $data, $vals, $index);
        xml_parser_free($p);


        $tree = array();
        $i = 0;
        $tree = get_children($vals, $i);

        return $tree;
    }

    protected function getChildren($vals, &$i) {
        /**
         * From http://phptoy.googlecode.com/svn
         */
        $children = array();

        if (isset($vals[$i]['value'])) {
            if ($vals[$i]['value'])
                array_push($children, $vals[$i]['value']);
        }

        $prevtag = "";
        $j = 0;

        while (++$i < count($vals)) {
            switch ($vals[$i]['type']) {
                case 'cdata':
                    array_push($children, $vals[$i]['value']);
                    break;
                case 'complete':
                    $name = get_name($vals[$i]);

                    /* if the value is an empty string, php doesn't include the 'value' key
                      in its array, so we need to check for this first */
                    if (isset($vals[$i]['value'])) {
                        $children{($name)} = $vals[$i]['value'];
                    } else {
                        $children{($name)} = "";
                    }

                    break;
                case 'open':
                    $name = get_name($vals[$i]);
                    $j++;

                    if ($prevtag <> $name) {
                        $j = 0;
                        $prevtag = $name;
                    }

                    $children{($name)} = get_children($vals, $i);
                    break;
                case 'close':
                    return $children;
            }
        }
    }

    function getNodeName($tag) {
        $name = $tag['tag'];
        if (isset($tag['attributes'])) {
            if (isset($tag['attributes']['class'])) {
                $name .= '.' . implode(".", explode(" ", $tag['attributes']['class']));
            }
            if (isset($tag['attributes']['id'])) {
                $name .= '#' . implode("#", explode(" ", $tag['attributes']['class']));
            }
        }
        return $name;
    }

}