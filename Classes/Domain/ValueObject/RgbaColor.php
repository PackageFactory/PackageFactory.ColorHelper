<?php

declare(strict_types=1);

namespace PackageFactory\ColorHelper\Domain\ValueObject;

class RgbaColor extends AbstractColor implements ColorInterface
{
    /**
     * @var int
     */
    private $red;

    /**
     * @var int
     */
    private $green;

    /**
     * @var int
     */
    private $blue;

    /**
     * @var int
     */
    private $alpha;

    /**
     * RgbColor constructor.
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param int $alpha
     */
    public function __construct(int $red, int $green, int $blue, int $alpha = 255)
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
     * @return int
     */
    public function getRed(): int
    {
        return $this->red;
    }

    /**
     * @return int
     */
    public function getGreen(): int
    {
        return $this->green;
    }

    /**
     * @return int
     */
    public function getBlue(): int
    {
        return $this->blue;
    }

    /**
     * @return int
     */
    public function getAlpha(): int
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
     */
    public function asHsla(): HslaColor
    {
        $r = $this->red / 255;
        $g = $this->green / 255;
        $b = $this->blue / 255;
        $Cmax = max($r, $g, $b);
        $Cmin = min($r, $g, $b);
        $lambda = $Cmax - $Cmin;
        $l = ($Cmax + $Cmin) / 2;

        if ($lambda == 0) {
            $hue = 0;
        } elseif ($Cmax == $r) {
            $hue = 60 * ((($g - $b) / $lambda) % 6);
        } elseif ($Cmax == $g) {
            $hue = 60 * ((($b - $r) / $lambda) + 2);
        } elseif ($Cmax == $b) {
            $hue = 60 * ((($r - $g) / $lambda) + 4);
        } else {
            throw new \UnexpectedValueException('this should never be thrown');
        }

        if ($hue < 0) {
            $hue += 360;
        }
        if ($hue > 359) {
            $hue -= 360;
        }

        if ($lambda == 0) {
            $s = 0;
        } else {
            $s = $lambda / (1 - abs((2 * $l) - 1));
        }

        $lightness = $l * 100;
        $saturation = $s * 100;

        return new HslaColor(
            (int) round($hue),
            (int) round($saturation),
            (int) round($lightness),
            $this->alpha
        );
    }

    /**
     * @param int $delta
     *
     * @return ColorInterface
     */
    public function withAdjustedAlpha(int $delta): ColorInterface
    {
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
