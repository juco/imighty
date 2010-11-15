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

    public function mixStyles($style, $styles) {

        foreach($styles as $style_name) {
            if(isset($this->styles[$style_name])) {
                echo $style_name.": ".get_class($style)." ";
                $style->mix($this->styles[$style_name]);
            }
        }
    }

    public function write($text) {
        $this->touch();
        $this->text = $text;
        return $this;
    }

    public function render() {
        if ($this->is_rendered === false) {
            $text = $this->processText($this->getHtmlDom(), $this->style());
            $this->renderer()->text()->write($text);
        }
        parent::render();
    }

    public function processText($blocks = false, $current_style = false) {

        $out = array();
        foreach($blocks as $name => $block) {
            if(!is_int($name)) {
                preg_match_all('/[\.#]?[a-zA-Z0-9]{1,}/', $name, $matches);
                echo get_class($current_style);
                $current_style = $this->mixStyles($current_style, $matches[0]);
            }
            $out[$name] = array();

            if(is_array($block)) {
                $out[$name]['value'] = $this->processText($block, $current_style);

            } else {
                $out[$name]['value'] = $block;
                $out[$name]['style'] = $current_style;
            }
        }
        return $out;
    }

    public function getHtmlDom() {
        $text = $this->text;
        if (!preg_match('/^\s*<root/', $text)) {
            $text = '<root>' . $text . '</root>';
        }

        return $this->getHtmlStructure($text);
    }

    protected function getHtmlStructure($data) {
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
        $tree = $this->getNodeChildren($vals, $i);

        return $tree;
    }

    protected function getNodeChildren($vals, &$i) {
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
                    $name = $this->getNodeName($vals[$i]);

                    /* if the value is an empty string, php doesn't include the 'value' key
                      in its array, so we need to check for this first */
                    if (isset($vals[$i]['value'])) {
                        $children{($name)} = $vals[$i]['value'];
                    } else {
                        $children{($name)} = "";
                    }

                    break;
                case 'open':
                    $name = $this->getNodeName($vals[$i]);
                    $j++;

                    if ($prevtag <> $name) {
                        $j = 0;
                        $prevtag = $name;
                    }

                    $children{($name)} = $this->getNodeChildren($vals, $i);
                    break;
                case 'close':
                    return $children;
            }
        }
    }

    protected function getNodeName($tag) {
        $name = $tag['tag'];
        if (isset($tag['attributes'])) {
            if (isset($tag['attributes']['class'])) {
                $name .= '.' . implode(".", explode(" ", $tag['attributes']['class']));
            }
            if (isset($tag['attributes']['id'])) {
                $name .= '#' . implode("#", explode(" ", $tag['attributes']['id']));
            }
        }
        return $name;
    }
    
    public  function save($filename) {
        
        if(false === $this->is_rendered) {
            $this->render();
        }
    }

}