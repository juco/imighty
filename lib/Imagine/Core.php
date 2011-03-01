<?php
require_once("Configuration.php");

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
 * The core class of Imagine
 *
 * @package     Imagine
 * @author      Alfonso de la Osa <alfonso.delaosa@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        https://github.com/botverse/imagine
 */
class ImagineCore {

    protected static
            $default_configuration = false,
            $classes = array(),
            $registered_autoload_dirs = array(),
            $registered_tools = array(),
            $registered_filters = array(),
            $registered_fonts = array(),
            $instance = false,
            $registered = false,
            $included_vendors = array();

    private $configuration = false;

    public function __construct($configuration) {

        if(false === self::$registered) {
            self::register();
        }

        if(false !== $configuration) {

            if(get_class($configuration) == "ImagineConfiguration" || is_subclass_of($configuration, "ImagineConfiguration")) {
                $this->configuration = $configuration;
            } else {
                throw new Exception("Wrong configuration passed.");
            }
        } else {
            if(false !== self::$default_configuration) {
                $this->configuration = self::$default_configuration;
            } else {
                $this->configuration = new ImagineConfiguration();
            }
        }
    }

    public function __call($name, $arguments) {

        if(!in_array($name, array_keys(self::$registered_tools))) {
            throw new Exception("Module not loaded: ".$name);
        }

        $reflection = new ReflectionClass(self::$registered_tools[$name]);

        array_unshift($arguments, $this);
        $layer = $reflection->newInstanceArgs($arguments);
        return $layer;
    }
    public function filter($name) {
        if(isset($this)) {
            $instance = $this;
        } else {
            $instance = self::getInstance();
        }
        $class = "ImagineFilter".ucfirst(strtolower($name));
        $reflection = new ReflectionClass($class);
        $layer = $reflection->newInstance();
        
        return $layer;
    }
    public function getConfiguration() {
        return $this->configuration;
    }
    public function setConfiguration($configuration) {
        $this->configuration = $configuration;
    }
    public static function configuration($options){
        self::setDefaultConfiguration(new ImagineConfiguration($options));
    }
    public static function getDefaultConfiguration() {
        return self::$default_configuration;
    }
    public static function setDefaultConfiguration($configuration) {
        self::$default_configuration = $configuration;
    }
    /**
     *
     * @param string $name
     * @param array $arguments
     * @return ImagineLayerLayer
     */
    public static function __callStatic($name, $arguments) {

        return call_user_func_array(array(self::getInstance(), $name), $arguments);
    }

//    public static function filter($name, $params){
//        return call_user_func(array(self::getInstance(), 'filter'), $params);
//    }
    /**
     * Devuelve una instancia de Imagine (Singleton)
     * @param ImagineConfiguration $configuration
     * @return ImagineCore
     */
    public static function getInstance($configuration = false) {

        if(false === self::$instance) {
            $class = get_called_class();
            $imagine = new $class($configuration);
            self::$instance = $imagine;
        }

        return self::$instance;
    }

    public static function isRegistered() {
        return (true === self::$registered);
    }
    public static function includeVendor($name){
        if(!isset(self::$included_vendors[$name])){
            require_once(realpath(__DIR__.'/../vendor').'/'.$name);
            self::$included_vendors[$name] = true;
        }
    }
    public static function register() {

        if(false !== self::$registered) {
            throw new Exception("Already registered.");
        }

        $class = get_called_class();
        
        if(method_exists($class, "configureAutoload")) {
            $dirs = call_user_func($class."::configureAutoload");
        }
        self::registerAutoloadDirs($dirs);

        self::registerAutoloadClasses($class, "Tools");
        self::registerAutoloadClasses($class, "Filters");

        if(method_exists($class, "getFontsDir")) {
            $font_dir = call_user_func($class."::getFontsDir");
        } else {
            $font_dir = realpath(__DIR__.'/../asset/fonts');
        }
        self::registerFonts($class, $font_dir);

        self::$registered = true;
    }
    protected static function registerAutoloadClasses($class, $type) {

        if(method_exists($class, 'configure'.$type)) {

            $classes = call_user_func($class.'::configure'.$type);
            if(is_array($classes)) {

                foreach(array_keys($classes) as $nickname) {
                    if(method_exists($class, $nickname)) {
                        throw new Exception($nickname." tool cannot be defined, reserved name.");
                    }
                }
                $atribute = 'registered_'.strtolower($type);
                self::$$atribute = $classes;
            }
        }
    }
    protected static function registerAutoloadDirs($dirs = array()) {



        if(!sizeof(self::$registered_autoload_dirs)) {
            spl_autoload_register(array("ImagineCore", "autoload"));
        }
        array_push($dirs, dirname(__FILE__));

        $current_path = preg_replace('/Imagine\/$/', '', __DIR__);

        foreach($dirs as $dir) {
            if(!in_array($dir, self::$registered_autoload_dirs)) {

                if(!is_dir($dir)) {
                    throw new Exception($dir." is not a directory.");
                }

                $files = self::rglob("*.php", $dir);
                $classes = array();

                foreach($files as $file) {
                    if(false !== strpos($file, $current_path)) {
                        $classname = 'Imagine'.substr(str_replace(array("/","\\"), '', substr($file, strlen($current_path))), 0, -4);
                    } else {
                        $classname = substr(basename($file), 0, -4);
                    }
                    $classes[$classname] = $file;
                }
                self::$classes = array_merge(self::$classes, $classes);
                array_push(self::$registered_autoload_dirs, $dir);
                
            }
        }
    }

    public static function registerFonts($class, $dir){
        $dirname = $dir;
        if(method_exists($class, 'configureFonts')){
            $fonts = call_user_func($class.'::configureFonts');
            if(is_array($fonts)) {
                foreach($fonts as $name => $font){
                    if(!preg_match('/\.ttf$/', $font)){
                        $font .= '.ttf';
                    }
                    self::$registered_fonts[$name] = $dirname.'/'.$font;
                }
            }
        }
    }

    public static function getFont($font){
        if(!isset(self::$registered_fonts[$font])){
            throw new Exception('Unknown font: '.$font);
        }
        return self::$registered_fonts[$font];
    }

    public static function autoload($class) {

        if(!isset(self::$classes[$class])) {
            return false;
        }
        if(!is_file(self::$classes[$class])) {
            return false;
        }

        require(self::$classes[$class]);
    }

    public static function registerLayer($name, $class) {

        if(!in_array($name, array_keys(self::$registered))) {
            self::$registered_tools[$name] = $class;
        }
    }

    public static function rglob($pattern, $path = '', $flags = 0) {
        $paths = glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
        $files = glob($path.$pattern, $flags);
        foreach ($paths as $path) {
            $files = array_merge($files, self::rglob($pattern, $path, $flags));
        }
        return $files;
    }
}