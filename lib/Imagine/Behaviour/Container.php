<?php

class ImagineBehaviourContainer extends ImagineBehaviourPositionable {

    protected
    $children = array(),
    $parent = false;

    public function getParent() {
        if (false === $this->parent) {
            throw new Exception('This has no parent.');
        }
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function hasParent() {
        return (false !== $this->parent);
    }
    public function hasChildrend(){
        return (bool) sizeof($this->children);
    }

    public function append($element) {
        $this->touch();
        if (is_subclass_of($element, "ImagineBehaviourPositionable")) {
            $element->setParent($this);
            array_push($this->children, $element);
        } else {
            throw new Exception("Unknown type of element: " . get_class($element));
        }
        return $this;
    }

    public function appendTo($element) {
        $element->append($this);
        return $this;
    }

    public function down($imagine) {
        return $this->getParent()->move($this, $imagine);
    }

    public function up($imagine) {
        return $this->getParent()->move($this, $imagine, 'up');
    }

    protected function move($elem, $target, $dir = 'down') {
        $this->touch();
        $pos = $this->findPosition($elem);
        unset($this->children[$pos]);
        $this->children = array_values($this->children);
        $target_pos = $this->findPosition($target);
        if ($dir === 'up') {
            $target_pos++;
        }
        array_splice(
                $this->children,
                $target_pos,
                count($this->children),
                array_merge(array($elem), array_slice($this->children, $target_pos))
        );
        return $this;
    }

    public function findPosition($imagine) {
        foreach ($this->children as $i => $child) {
            if ($imagine === $child) {
                return $i;
            }
        }
        return false;
    }

    public function render() {
        $this->clearRenderStack();


        foreach ($this->children as $child) {
            $this->addToRenderStack($child);
        }

        return parent::render();
    }
    /**
     * Unused
     *
     * @return array
     */
    public function getChildBoundaries() {
        $boundaries = parent::getBoundaries();
        $borders = array(
            "left" => true,
            "right" => false,
            "top" => true,
            "bottom" => false
        );
        foreach ($this->children as $child) {
            $child_boundaries = $child->getBoundaries();
            foreach ($borders as $border => $direction) {
                if ($direction && $boundaries[$border] > $child_boundaries[$border]) {
                    $boundaries[$border] = $child_boundaries[$border];
                } else if (!$direction && $boundaries[$border] < $child_boundaries[$border]) {
                    $boundaries[$border] = $child_boundaries[$border];
                }
            }
        }
        return $boundaries;
    }

    public function getDimmension() {

        $boundaries = $this->getBoundaries();
        $dimmension = parent::getDimmension();

        if ($dimmension["width"] === 0) {
            $dimmension["width"] = $boundaries["right"] - $boundaries["left"];
        }
        if ($dimmension["height"] === 0) {
            $dimmension["height"] = $boundaries["bottom"] - $boundaries["top"];
        }
        return $dimmension;
    }

}