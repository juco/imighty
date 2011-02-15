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
class ImagineRendererGdTextRenderer {

    protected
    $renderer = false;

    public function __construct(ImagineRendererGd $renderer) {
        $this->renderer = $renderer;
        $this->exp = 2;
    }
    /**
     *
     * @return ImagineRendererGd
     */
    public function getRenderer() {
        return $this->renderer;
    }

    protected function render($blocks, $layer_width) {

        $real_height = $this->getRealHeight($blocks);
        $scaled_img = imagecreatetruecolor($layer_width * $this->exp, $real_height * $this->exp);

        $background_color = imagecolortransparent(
                $scaled_img,
                imagecolorallocatealpha($scaled_img, 0, 0, 0, 127));

        imagefill($scaled_img, 0, 0, $background_color);

        // render backgrounds
        foreach ($blocks as $block) {
            $this->renderBackgroundBlock($block, $block['style'], $scaled_img);
        }

        // render text
        foreach ($blocks as $block) {
            $this->renderTextBlock($block, $block['style'], $scaled_img);
        }

        $output_img = imagecreatetruecolor($layer_width, $real_height);

        $background = $this->getRenderer()->getLayer()->background();
        if(false === $background){
            $transparent_color = imagecolortransparent(
                    $output_img,
                    imagecolorallocatealpha($output_img, 0, 0, 0, 127));
        } else {
            $bg = ImagineRendererGd::hex_to_rgb($background);
            $transparent_color = imagecolorallocate($output_img, $bg['r'], $bg['g'], $bg['b']);
        }
        imagefill($output_img, 0, 0, $transparent_color);
        imagecopyresampled(
                $output_img, $scaled_img,
                0, 0, 0, 0,
                $layer_width, $real_height, $layer_width * $this->exp, $real_height * $this->exp);
        $data = array(
            'width' => $layer_width,
            'height' => $real_height,
            'resource' => $output_img
        );
        
        $this->renderer->sendData($data);
    }

    protected function getRealHeight($blocks) {
        $last_chunk = $blocks[sizeof($blocks) - 1];

        $y = $last_chunk['position']['y'];
        return $y + $last_chunk['style']->line_height();
    }

    protected function renderBackgroundBlock($block, $style, $im) {
        if (!$style->background()) {
            return;
        }
        $background = ImagineRendererGd::hex_to_rgb($style->background());
        $background_alloc = imagecolorallocate($im, $background['r'], $background['g'], $background['b']);

        $x = $block['position']['x'] * $this->exp;
        $y = $block['position']['y'] * $this->exp;

        $line_height = $style->line_height() * $this->exp;
        $width = $block['width'] * $this->exp;

        imagefilledrectangle($im, $x, $y, $x + $width, $y + $line_height, $background_alloc);
    }

    protected function renderTextBlock($block, $style, $im) {
        $color = ImagineRendererGd::hex_to_rgb($style->color());
        $color_alloc = imagecolorallocate($im, $color['r'], $color['g'], $color['b']);

        $x = $block['position']['x'] * $this->exp;
        $y = $block['position']['y'] * $this->exp;

        $line_height = $style->line_height() * $this->exp;
        $width = $block['width'] * $this->exp;

        $size = $style->size() * $this->exp;
        $size_pt = $size * 72 / 96;

        imagettftext(
                $im,
                $size_pt,
                0,
                $x,
                $this->yToLine($y, $line_height, $size),
                $color_alloc,
                $block['style']->getFont(),
                $block['text']
        );
    }

    //tools
    public function getMetrics($text, $style) {
        $box = imagettfbbox($style->size() * 72 / 96 * $this->exp, 0, $style->getFont(), $text);
        $width = $box[0] / $this->exp + $box[2] / $this->exp;

        $height = $box[7] / $this->exp - $box[1] / $this->exp;
        return array('width' => $width, 'height' => $height);
    }

    protected static function yToLine($y, $line_height, $size) {
        return round($y + $line_height / 2 + $size / 3);
    }

}

?>
