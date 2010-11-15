<?php

function xml2array($data) {
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

function get_children($vals, &$i) {
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

function get_name($tag) {
    $name = $tag['tag'];
    if(isset($tag['attributes'])){
        if(isset($tag['attributes']['class'])){
            $name .= '.'.implode(".", explode(" ", $tag['attributes']['class']));
        }
        if(isset($tag['attributes']['id'])){
            $name .= '#'.implode("#", explode(" ", $tag['attributes']['class']));
        }
    }
    return $name;
}

$arrOutput = xml2array('<root>hola <b class="verde">mundo<span>joder</span></b> cruel.<p>man!</p></root>');
var_dump($arrOutput); //print it out, or do whatever!
?>
