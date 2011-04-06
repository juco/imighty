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
abstract class ImagineBehaviourPositionable extends ImagineBehaviourSizable {

    abstract public function getParent();
    abstract public function setParent($parent);

    protected
            $top = 0,
            $left = 0,
            $right = false,
            $bottom = false,
            $position = "relative";

    private static $borders = array(
            "top" => array(
                            "opposite" => "bottom",
                            "orientation" => "height",
                            "operator" => 1
            ),
            "bottom" => array(
                            "opposite" => "top",
                            "orientation" => "height",
                            "operator" => -1
            ),
            "left" => array(
                            "opposite" => "right",
                            "orientation" => "width",
                            "operator" => 1
            ),
            "right" => array(
                            "opposite" => "left",
                            "orientation" => "width",
                            "operator" => -1
            )
    );


    /*
     *
    */
    // TODO: This should be not magic
    protected function configure(){
        $this->configureRenderOption('boundaries');
        parent::configure();
    }

    public function top(){
        $args = func_get_args();
        return $this->border('top', $args);
    }
    public function left(){
        $args = func_get_args();
        return $this->border('left', $args);
    }
    public function right(){
        $args = func_get_args();
        return $this->border('right', $args);
    }
    public function bottom(){
        $args = func_get_args();
        return $this->border('bottom', $args);
    }

    public function border($border,  $arguments) {

        if(in_array($border, array_keys(self::$borders))) {

            if(sizeof($arguments) > 1) {
                throw new Exception($border." accepts only 1 argument.");
            }
            $this->touch(); 
            $value = false;
            if(sizeof($arguments) === 1) {
                $value = $arguments[0];
            }
            if($value === false) {
                return $this->$border;
            }

            $opp = self::$borders[$border]["opposite"];
            $this->$opp = false;
            $this->$border = $value;
            return $this;
        } else {
            throw new Exception($border.' is not a property');
        }
    }

    public function getPosition() {
        return array(
                "left" => $this->left,
                "right" => $this->right,
                "top" => $this->top,
                "bottom" => $this->bottom
        );
    }

    public function getBoundaries() {
        if(!$this->hasParent()) {
            return array(
                    "left" => 0,
                    "right" => $this->width(),
                    "top" => 0,
                    "bottom" => $this->height()
            );
        }
        $boundaries = array();
        $borders = $this->getBorders();

        $pdim = $this->getParent()->getDimmension();
        $dim = parent::getDimmension();
        $margins = $this->margin();
        $paddings = $this->padding();
        foreach($borders as $border => $options) {
            if(isset($borders[$border])) {
                $opposite = $temp = $options["opposite"];
                if(false === $this->$border) {
                    $opposite = $border;
                    $border = $temp;
                    
                }
                unset($temp);
                $boundaries[$border] = $this->$border + $options['operator'] * ($margins[$border] + $paddings[$border]);
                $add = $this->$border + $dim[$options['orientation']];
                $boundaries[$opposite] = $pdim[$options["orientation"]] - ($add + $margins[$border] + $paddings[$border])  * $options["operator"];
                unset($borders[$border], $borders[$opposite]);
            }
        }

        return $boundaries;
    }
    public function render() {
        if($this->hasParent()) {
            $this->setRenderOption("boundaries", $this->getBoundaries());
        }
        parent::render();
    }
    public function getBorders() {
        return self::$borders;
    }

    protected function cmp($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}