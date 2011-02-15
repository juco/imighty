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
class ImagineFilterGrayscale  extends ImagineFilterFilter {
    //put your code here

    protected
            $white = "#ffffff",
            $black = "#000000",
            $colorize = false;

    public function getWhite() {
        return $this->white;
    }
    public function getBlack() {
        return $this->black;
    }
    public function getColor($color) {
        if($color === false) {
            $this->colorize = true;
        }
        return $this->color;
    }
    public function white($white) {
        $this->touch();
        $this->white = $white;
        return $this;
    }
    public function black($black) {
        $this->touch();
        $this->black;
        return $this;
    }
    public function color($color) {

        $this->touch();
        $this->color = $color;
        return $this;
    }
    protected function proccessColor($c) {
        if(strlen($c) == 3) {
            $c = $c[0].$c[0].$c[1].$c[1].$c[2].$c[2];
        }
        preg_match("/(.{2})(.{2})(.{2})/", $c, $matches);
        list($rgb, $r, $g, $b) = $matches;
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return array($r, $g, $b);
    }
    public function renderGd($data) {
        $output = array(
                'width' => $data['width'],
                'height' => $data['height'],
                'resource' => imagecreatetruecolor($data['width'], $data['height'])
        );
        imagecopy($output['resource'], $data['resource'], 0, 0, 0, 0, $data['width'], $data['height']);
        imagefilter($output['resource'], IMG_FILTER_GRAYSCALE);
        if($this->color) {
            $params = array_merge(array($output['resource'], IMG_FILTER_COLORIZE), $this->proccessColor($this->color));
            call_user_func_array('imagefilter', $params);
        }

        return $output;
    }
}
?>
