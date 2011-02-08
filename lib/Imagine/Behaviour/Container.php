<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * http://www.opensource.org/licenses/lgpl-license.php.
 */

/**
 *
 * @package     Imagine
 * @author      Alfonso de la Osa <alfonso.delaosa@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        https://github.com/botverse/imagine
 */
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