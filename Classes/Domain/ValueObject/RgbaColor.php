<?php

declare(strict_types=1);

namespace PackageFactory\ColorHelper\Domain\ValueObject;

use mysql_xdevapi\Exception;

class RgbaColor extends AbstractColor implements ColorInterface
{
    /**
     * @var float
     */
    private $red;

    /**
     * @var float
     */
    private $green;

    /**
     * @var float
     */
    private $blue;

    /**
     * @var float
     */
    private $alpha;

    /**
     * RgbColor constructor.
     *
     * @param float $red
     * @param float $green
     * @param float $blue
     * @param float $alpha
     */
    public function __construct(float $red, float $green, float $blue, float $alpha = 255)
    {
        if ($red < 0 || $red > 255) {
            throw new \InvalidArgumentException('argument red has to be an integer between 0 and 255');
        }
        if ($green < 0 || $green > 255) {
            throw new \InvalidArgumentException('argument green has to be an integer between 0 and 255');
        }
        if ($blue < 0 || $blue > 255) {
            throw new \InvalidArgumentException('argument blue has to be an integer between 0 and 255');
        }
        if ($alpha < 0 || $alpha > 255) {
            throw new \InvalidArgumentException('argument alpha has to be an integer between 0 and 255');
        }

        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    /**
     * @return float
     */
    public function getRed(): float
    {
        return $this->red;
    }

    /**
     * @return float
     */
    public function getGreen(): float
    {
        return $this->green;
    }

    /**
     * @return float
     */
    public function getBlue(): float
    {
        return $this->blue;
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
     */
    public function asRgba(): self
    {
        return $this;
    }

    /**
     * @return HslaColor
     *
     * @see http://en.wikipedia.org/wiki/HSL_color_space.
     * @see https://gist.github.com/mjackson/5311256
     */
    public function asHsla(): HslaColor
    {
        $r = $this->red / 255;
        $g = $this->green / 255;
        $b = $this->blue / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $l = ($max + $min) / 2;

        if ($max == $min) {
            $h = $s = 0;
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

            switch ($max) {
                case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
                case $g: $h = ($b - $r) / $d + 2; break;
                case $b: $h = ($r - $g) / $d + 4; break;
                default:
                    throw  new Exception('this should never happen');
            }

            $h /= 6;
        }

        return new HslaColor(
            $h * 359,
            $s * 100,
            $l * 100,
            $this->alpha / 255
        );
    }

    /**
     * @param float $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedAlpha(float $delta): ColorInterface
    {
        $delta = $delta / 100 * 255;
        $alpha = $this->getAlpha() + $delta;
        if ($alpha < 0) {
            $alpha = 0;
        }
        if ($alpha > 255) {
            $alpha = 255;
        }

        return new self(
            $this->red,
            $this->green,
            $this->blue,
            $alpha
        );
    }
}
