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
 * The base configuration class of Imagine
 *
 * @package     Imagine
 * @author      Alfonso de la Osa <alfonso.delaosa@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        https://github.com/botverse/imagine
 */
class ImagineConfiguration {

    protected static
            $settings = array(
                "output" => "",
                "input" => "",
                "renderer" => "Gd"
            ),
            $protected_options = array();
    protected $options = array();

    public function __construct($options = array()) {

        $this->options = self::$settings;

        foreach($options as $key => $value) {
            if(in_array($key, array_keys($this->options))) {
                $this->$key = $value;
            } else {
                throw new Exception("Wrong options passed.");
            }
        }

    }

    public function  __set($name,  $value) {
        if(!key_exists($name, $this->options)) {
            throw new Exception ("set Unknown option: ".$name);
        }

        $this->options[$name] = $value;
    }
    public function __get($name) {
        if(!key_exists($name, $this->options)) {
            throw new Exception("get Unknown option: ". $name);
        }

        return $this->options[$name];
    }

    public function __call($name,  $arguments) {
        $property = $this->decamelize($name);

        if(key_exists($property, $this->options)) {
            if(sizeof($arguments) == 0) {
                return $this->$property;

            } else if (sizeof($arguments) == 1) {
                $this->$property = $arguments[0];
                return $this;

            } else {
                throw new Exception("Only one argument for this setter: ".$name);
            }
        } else {
            throw new Exception("Property not found: ". $property);
        }
    }








    protected function camelize($text) {
        return preg_replace(array('#/(.?)#e', '/(^|_|-)+(.)/e'), array("'::'.strtoupper('\\1')", "strtoupper('\\2')"), $text);
    }

    protected function decamelize($str) {
        $tokens = preg_split('/([A-Z])/', $str, null, (PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE));
        $result = array();
        $len = count($tokens);
        if ($len == 1) {
            return $str;
        }
        $result[] = $tokens[0];
        for ($i = 1; $i < $len; $i += 2) {
            $r = strtolower($tokens[$i]);
            if (isset($tokens[$i+1])) {
                $r .= $tokens[$i+1];
            }
            $result[] = $r;
        }
        return implode('_', $result);
    }
}