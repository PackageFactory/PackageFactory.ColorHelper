<?php

declare(strict_types=1);

namespace PackageFactory\ColorHelper\Domain\ValueObject;

use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Proxy(false)
 */
class HslaColor extends AbstractColor implements ColorInterface
{
    /**
     * @var float
     */
    private $hue;

    /**
     * @var float
     */
    private $saturation;

    /**
     * @var float
     */
    private $lightness;
    /**
     * @var float
     */
    private $alpha;

    /**
     * HslaColor constructor.
     *
     * @param float $hue
     * @param float $saturation
     * @param float $lightness
     * @param float $alpha
     */
    public function __construct(float $hue, float $saturation, float $lightness, float $alpha = 1)
    {
        if ($hue < 0 || $hue > 360) {
            throw new \InvalidArgumentException('argument hue has to be a float between 0 and 359, '.$hue.' was given.');
        }
        if ($saturation < 0 || $saturation > 100) {
            throw new \InvalidArgumentException('argument saturation has to be a float between 0 and 100, '.$saturation.' was given.');
        }
        if ($lightness < 0 || $lightness > 100) {
            throw new \InvalidArgumentException('argument luminosity has to be a float between 0 and 100, '.$lightness.' was given.');
        }
        if ($alpha < 0 || $alpha > 1) {
            throw new \InvalidArgumentException('argument alpha has to be a float between 0 and 1, '.$alpha.' was given');
        }

        $this->hue = $hue;
        $this->saturation = $saturation;
        $this->lightness = $lightness;
        $this->alpha = $alpha;
    }

    /**
     * @return float
     */
    public function getHue(): float
    {
        return $this->hue;
    }

    /**
     * @return float
     */
    public function getSaturation(): float
    {
        return $this->saturation;
    }

    /**
     * @return float
     */
    public function getLightness(): float
    {
        return $this->lightness;
    }

    /**
     * @return float
     */
    public function getAlpha(): float
    {
        return $this->alpha;
    }

    /**
     * @return RgbaColor
     *
     * @see http://en.wikipedia.org/wiki/HSL_color_space.
     * @see https://gist.github.com/mjackson/5311256
     */
    public function asRgba(): RgbaColor
    {
        $h = $this->hue / 360;
        $l = $this->lightness / 100;
        $s = $this->saturation / 100;
        $a = $this->alpha;

        if ($s == 0) {
            $rgb = $l * 255;

            return new RgbaColor($rgb, $rgb, $rgb, $this->alpha * 255);
        }

        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;

        $r = $this->hue2rgb($p, $q, $h + 1 / 3);
        $g = $this->hue2rgb($p, $q, $h);
        $b = $this->hue2rgb($p, $q, $h - 1 / 3);

        return new RgbaColor($r * 255, $g * 255, $b * 255, $a * 255);
    }

    /**
     * @param float $p
     * @param float $q
     * @param float $t
     *
     * @return float
     */
    private function hue2rgb(float $p, float $q, float $t): float
    {
        if ($t < 0) {
            $t += 1;
        }
        if ($t > 1) {
            $t -= 1;
        }
        if ($t < 1 / 6) {
            return $p + ($q - $p) * 6 * $t;
        }
        if ($t < 1 / 2) {
            return $q;
        }
        if ($t < 2 / 3) {
            return $p + ($q - $p) * (2 / 3 - $t) * 6;
        }

        return $p;
    }

    /**
     * @return HslaColor
     */
    public function asHsla(): HslaColor
    {
        return $this;
    }

    /**
     * @param float $delta 0..100
     *
     * @return ColorInterface
     */
    public function withAdjustedAlpha(float $delta): ColorInterface
    {
        $delta = $delta / 100;
        $alpha = $this->getAlpha() + $delta;
        if ($alpha < 0) {
            $alpha = 0;
        }
        if ($alpha > 1) {
            $alpha = 1;
        }

        return new self(
            $this->hue,
            $this->saturation,
            $this->lightness,
            $alpha
        );
    }
}
