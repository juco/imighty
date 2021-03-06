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
class ImagineRendererGd extends ImagineRendererRenderer {

  protected $imagine;
  protected $text = false;

  public function loadFile($filename = "") {
    $types = self::$_types;
    $_imagecreatefunction = self::$_core_settings["_imagecreatefunction"];

    if (!$types || !$_imagecreatefunction) {
      throw new Exception("No se han recibido los tipos de archivo, imagecreatefunction: " . $_imagecreatefunction);
    }

    $configuration = $this->getConfiguration();
    if (false !== $configuration->input) {
      $new_filename = rtrim($configuration->input, "/") . "/" . ltrim($filename, "/");
    }
    if (file_exists($new_filename)) {
      $filename = $new_filename;
    } else {
      if (!file_exists($filename)) {
        throw new Exception("Not a file: " . $filename);
      }
    }

    $info = getimagesize($filename);

    $imagedata = array(
        'width' => $info[0],
        'height' => $info[1],
        'bias' => ($info[0] >= $info[1]) ? "horizontal" : "vertical",
        'aspectratio' => $info[0] / $info[1],
        'type' => $info[2],
        'resource' => null
    );

    if ($types[$imagedata['type']]['supported'] < 1) {
      throw new Exception('Imagetype (' . $types[$imagedata['type']]['ext'] . ') not supported for reading.');
      return null;
    }

    switch ($imagedata['type']) {
      case 1:
        $imagedata['type_string'] = 'image/gif';
        $dummy = imagecreatefromgif($filename);
        $functionname = $_imagecreatefunction;
        $imagedata['resource'] = $functionname($imagedata['width'], $imagedata['height']);
        imagecopy($imagedata['resource'], $dummy, 0, 0, 0, 0, $imagedata['width'], $imagedata['height']);
        imagedestroy($dummy);
        break;

      case 2:
        $imagedata['type_string'] = 'image/jpeg';
        $imagedata['resource'] = imagecreatefromjpeg($filename);
        break;

      case 3:
        $imagedata['type_string'] = 'image/png';
        $dummy = imagecreatefrompng($filename);
        if (imagecolorstotal($dummy) != 0) {
          $functionname = $_imagecreatefunction;
          $imagedata['resource'] = $functionname($imagedata['width'], $imagedata['height']);
          imagecopy($imagedata['resource'], $dummy, 0, 0, 0, 0, $imagedata['width'], $imagedata['height']);
        } else {
          $imagedata['resource'] = $dummy;
        }
        unset($dummy);
        break;

      default:
        throw new Exception('Imagetype not supported.');
        return null;
    }

    $this->original_data = $imagedata;
  }

  public function getContentType() {
    return $this->original_data['type_string'];
  }

  public function createResource($dimmension) {
    $imagedata = array(
        'width' => $dimmension['width'],
        'height' => $dimmension['height'],
        'bias' => ($dimmension['width'] >= $dimmension['height']) ? "horizontal" : "vertical",
        'aspectratio' => $dimmension['width'] / $dimmension['height'],
        'type_string' => 'unknown',
        'resource' => imagecreatetruecolor($dimmension['width'], $dimmension['height'])
    );
    if($this->layer->background()){
      $bg = ImagineRendererGd::hex_to_rgb($this->layer->background());

      $bg_color = imagecolorallocate($imagedata['resource'], $bg['r'], $bg['g'], $bg['b']);
      imagefilledrectangle($imagedata['resource'], 0, 0, $dimmension['width'], $dimmension['height'], $bg_color);
    }
    $this->original_data = $imagedata;
  }

  public function saveFile($filename, $quality=75) {

    $configuration = $this->getConfiguration();

    if (false !== $configuration->output) {
      $new_filename = rtrim($configuration->output, "/") . "/" . ltrim($filename, "/");
    }

    if (is_dir(dirname($new_filename))) {
      $filename = $new_filename;
    } else {
      if (!is_dir(dirname($filename))) {
        throw new Exception("Not a dir: " . dirname($filename));
      }
    }

    $path = pathinfo($new_filename);
    switch ($path['extension']) {
      case 'jpg':
        imagejpeg($this->getResource(), $new_filename, $quality);
        $this->rendered_data['type_string'] = 'image/jpeg';
        break;
      case 'png':
        
        $data = $this->pickData();
        $im = $data['resource'];
        //imagetruecolortopalette($im, false, 2);
        imagesavealpha($im, true);

        $palette = imagecreate($this->getWidth(), $this->getHeight());
        imagecopy($palette, $im, 0, 0, 0, 0, $this->getWidth(), $this->getHeight());
        imagepng($palette, $new_filename);
        $data['resource'] = $palette;
        $this->sendData($data);

        $this->rendered_data['type_string'] = 'image/png';
        break;
    }
    chmod($new_filename, 0777);
  }

  public function toBrowser($quality = 75) {

    $im = $this->pickData();
    header('Content-Type: ' . $im['type_string']);
    // Output the image
    switch ($im['type_string']) {
      case 'image/jpeg':
      case 'image/jpg':
        imagejpeg($im['resource'], null, $quality);
        break;
      case 'image/png':
        imagepng($im['resource'], null);
        break;
    }
    // Free up memory
    imagedestroy($im['resource']);

    exit();
  }

  public function resize() {
    $data = $this->pickData();

    $offset = $this->getRenderOption('offset');
    $multiplier = $this->getRenderOption('multiplier');
    $dimmension = $this->getRenderOption('dimmension');

    $width = $dimmension['width'] ? $dimmension['width'] : $data['width'];
    $height = $dimmension['height'] ? $dimmension['height'] : $data['height'];

    $new_image = imagecreatetruecolor($width, $height);
    imagecopyresampled(
            $new_image,
            $data['resource'],
            0,
            0,
            $offset['left'] * $multiplier['left'],
            $offset['top'] * $multiplier['top'],
            $width,
            $height,
            $data['width'] - $offset['left'],
            $data['height'] - $offset['top']
    );
    $this->rendered_data['width'] = $width;
    $this->rendered_data['height'] = $height;
    $this->rendered_data['resource'] = $new_image;

    $this->is_rendered = true;
  }

  public function mix($child) {
    $data = $this->pickData();
    $renderer = $child->getRenderer();
    $child_resource = $renderer->getResource();
    $position = $renderer->getRenderOption('boundaries');
    $background = $child->background();

    if ($background != 'transparent') {

      $bg = ImagineRendererGd::hex_to_rgb($background);
      $padding = $child->padding();
      $bg_color = imagecolorallocate($data['resource'], $bg['r'], $bg['g'], $bg['b']);
      $x1 = $position['left'] - $padding['left'];
      $y1 = $position['top'] - $padding['top'];
      $x2 = $data['width'] - $position['right'] + $padding['right'];
      $y2 = $data['height'] - $position['bottom'] + $padding['bottom'];

      imagefilledrectangle(
              $data['resource'],
              $x1,
              $y1,
              $x2,
              $y2,
              $bg_color
      );
    }
    imagecopy(
            $data['resource'],
            $child_resource,
            $position['left'],
            $position['top'],
            0,
            0,
            $renderer->getWidth(),
            $renderer->getHeight()
    );

    $this->sendData($data);
    $this->is_rendered = true;
  }

  /**
   *
   * @return ImagineRendererGdText
   */
  public function text() {
    if (false === $this->text) {
      $this->text = new ImagineRendererGdText($this);
    }
    return $this->text;
  }

  /*
   * Static
   */

  protected static
  $_core_settings = array();
  protected static $_types = array(
      1 => array(
          'ext' => 'gif',
          'mime' => 'image/gif',
          'supported' => 0
      ),
      2 => array(
          'ext' => 'jpg',
          'mime' => 'image/jpeg',
          'supported' => 0
      ),
      3 => array(
          'ext' => 'png',
          'mime' => 'image/png',
          'supported' => 0
      )
  );

  public static function initCore() {
    $settings = array();
    $settings["gd_info"] = function_exists('gd_info') ? gd_info() : $this->_gd_info();
    preg_match("/\A[\D]*([\d+\.]*)[\D]*\Z/", $settings["gd_info"]['GD Version'], $matches);
    list($settings["_gd_version_string"], $settings["_gd_version_number"]) = $matches;
    $settings["_gd_version"] = substr($settings["_gd_version_number"], 0, strpos($settings["_gd_version_number"], '.'));
    if ($settings["_gd_version"] >= 2) {
      $settings["_imagecreatefunction"] = 'imagecreatetruecolor';
      $settings["_resize_function"] = 'imagecopyresampled';
    } else {
      $settings["_imagecreatefunction"] = 'imagecreate';
      $settings["_resize_function"] = 'imagecopyresized';
    }

    $settings["_gd_ttf"] = $settings["gd_info"]['FreeType Support'];
    $settings["_gd_ps"] = $settings["gd_info"]['T1Lib Support'];
    if ($settings["gd_info"]['GIF Read Support']) {
      self::$_types[1]['supported'] = 1;
      if ($settings["gd_info"]['GIF Create Support']) {
        self::$_types[1]['supported'] = 2;
      }
    }
    $key = 'JPEG Support';
    if (!isset($settings["gd_info"][$key])) {
      $key = 'JPG Support';
    }
    if ($settings["gd_info"][$key]) {
      self::$_types[2]['supported'] = 2;
    }
    if ($settings["gd_info"]['PNG Support']) {
      self::$_types[3]['supported'] = 2;
    }
    self::$_core_settings = $settings;
    self::$_core_initialized = true;
  }

  public static function hex_to_rgb($c) {
    $c = trim($c, ' #');
    if (strlen($c) == 3) {
      $c = $c[0] . $c[0] . $c[1] . $c[1] . $c[2] . $c[2];
    }
    preg_match("/(.{2})(.{2})(.{2})/", $c, $matches);
    list($whole, $r, $g, $b) = $matches;

    $rgb = array();
    $rgb['r'] = hexdec($r);
    $rgb['g'] = hexdec($g);
    $rgb['b'] = hexdec($b);

    return $rgb;
  }

}