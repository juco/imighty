<?php

class ImagineLayerText extends ImagineLayerLayer {

    protected
            $styles = array(),
            $text = '';


    public function style() {
        $args = func_get_args();
        if(isset($this->styles['default'])) {
            $default_opts = $this->styles['default']->getTouchedArray();
        } else {
            $default_opts = array();
        }

        if (sizeof($args) == 2 && is_array($args[1])) {

            $this->touch();
            if(isset($this->styles[$args[0]])) {

                $this->styles[$args[0]]->processArray($args[1]);
            } else {
                $pre = new ImagineToolStyle($default_opts);
                $pre->processArray($args[1]);
                $this->styles[$args[0]] = $pre;
            }
        } else if (sizeof($args)) {

            $this->touch();
            $this->styles = array_merge($this->styles, $args);
        } else {
            return new ImagineToolStyle($default_opts);
        }
        return $this;
    }

    public function mixStyles($style, $styles) {
        $style = new ImagineToolStyle($style);
        foreach($styles as $style_name) {
            if(isset($this->styles[$style_name])) {
                $style->mix($this->styles[$style_name]);
            }
        }
        return $style;
    }

    public function write($text) {
        $this->touch();
        $this->text = $text;
        return $this;
    }

    public function render() {
        if ($this->is_rendered === false) {
            $text = $this->processText($this->getHtmlDom(), $this->style());
            $this->getRenderer()->text()->write($text);
        }
        parent::render();
    }
    
    public function getHtmlDom() {
        $text = $this->text;
        if (!preg_match('/^\s*<root/', $text)) {
            $text = '<root>' . $text . '</root>';
        }

        return $this->getHtmlStructure($text);
    }

    public function processText($blocks = false, $current_style = false) {

        $out = array();
        foreach($blocks as $name => $block) {
            if(!is_int($name)) {
                preg_match_all('/[\.#]?[a-zA-Z0-9]{1,}/', $name, $matches);
                $style = $this->mixStyles($current_style, $matches[0]);
            } else {
                $style = $current_style;
            }
            $out[$name] = array();

            if(is_array($block)) {

                $out[$name]['value'] = $this->processText($block, $style);
            } else {
                $out[$name]['value'] = $block;
            }
            $out[$name]['style'] = $style;
        }
        return $out;
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
            if(sizeof($vals) == 1) {
                return $children;
            }
        }

        $prevtag = "";
        $j = 0;

        while($i++ < count($vals)) {
            switch ($vals[$i]['type']) {
                case 'cdata':
                    array_push($children, $vals[$i]['value']);
                    break;
                case 'complete':
                    $name = $this->getNodeName($vals[$i]).":".$i;

                    /* if the value is an empty string, php doesn't include the 'value' key
                      in its array, so we need to check for this first */
                    $arr = array();
                    if (isset($vals[$i]['value'])) {
                        $arr[$name] = $vals[$i]['value'];
                    } else {
                        $arr[$name] = "";
                    }
                    $children = array_merge($children, $arr);
                    break;
                case 'open':
                    $name = $this->getNodeName($vals[$i]).':'.$i;
                    $j++;

                    if ($prevtag <> $name) {
                        $j = 0;
                        $prevtag = $name;
                    }
                    $arr = array($name => $this->getNodeChildren($vals, $i));
                    $children = array_merge($children, $arr);
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
}