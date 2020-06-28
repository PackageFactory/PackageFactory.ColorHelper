<?php

declare(strict_types=1);

namespace PackageFactory\ColorHelper\Domain\ValueObject;

class HslaColor extends AbstractColor implements ColorInterface
{
    /**
     * @var int
     */
    private $hue;

    /**
     * @var int
     */
    private $saturation;

    /**
     * @var int
     */
    private $lightness;
    /**
     * @var int
     */
    private $alpha;

    /**
     * HslaColor constructor.
     *
     * @param int $hue
     * @param int $saturation
     * @param int $lightness
     * @param int $alpha
     */
    public function __construct(int $hue, int $saturation, int $lightness, int $alpha = 255)
    {
        if ($hue < 0 || $hue > 359) {
            throw new \InvalidArgumentException('argument hue has to be an integer between 0 and 359, '.$hue.' was given.');
        }
        if ($saturation < 0 || $saturation > 100) {
            throw new \InvalidArgumentException('argument saturation has to be an integer between 0 and 100, '.$saturation.' was given.');
        }
        if ($lightness < 0 || $lightness > 100) {
            throw new \InvalidArgumentException('argument luminosity has to be an integer between 0 and 100, '.$lightness.' was given.');
        }
        if ($alpha < 0 || $alpha > 255) {
            throw new \InvalidArgumentException('argument alpha has to be an integer between 0 and 255, '.$alpha.' was given');
        }

        $this->hue = $hue;
        $this->saturation = $saturation;
        $this->lightness = $lightness;
        $this->alpha = $alpha;
    }

    /**
     * @return int
     */
    public function getHue(): int
    {
        return $this->hue;
    }

    /**
     * @return int
     */
    public function getSaturation(): int
    {
        return $this->saturation;
    }

    /**
     * @return int
     */
    public function getLightness(): int
    {
        return $this->lightness;
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
    public function asRgba(): RgbaColor
    {
        $S = $this->saturation / 100;
        $L = $this->lightness / 100;
        $C = (1 - abs((2 * $L) - 1)) * $S;
        $X = $C * (1 - abs((($this->hue / 60) % 2) - 1));
        $m = $L - ($C / 2);

        if ($this->hue < 0) {
            throw new \UnexpectedValueException('this should never be thrown');
        } elseif ($this->hue < 60) {
            $r = $C;
            $g = $X;
            $b = 0;
        } elseif ($this->hue < 120) {
            $r = $X;
            $g = $C;
            $b = 0;
        } elseif ($this->hue < 180) {
            $r = 0;
            $g = $C;
            $b = $X;
        } elseif ($this->hue < 240) {
            $r = 0;
            $g = $X;
            $b = $C;
        } elseif ($this->hue < 300) {
            $r = $X;
            $g = 0;
            $b = $C;
        } elseif ($this->hue < 360) {
            $r = $C;
            $g = 0;
            $b = $X;
        } else {
            throw new \UnexpectedValueException('this should never be thrown');
        }

        $R = ($r + $m) * 255;
        $G = ($g + $m) * 255;
        $B = ($b + $m) * 255;

        return new RgbaColor(
            (int) round($R),
            (int) round($G),
            (int) round($B),
            $this->alpha
        );
    }

    /**
     * @return HslaColor
     */
    public function asHsla(): self
    {
        return $this;
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
            $this->hue,
            $this->saturation,
            $this->lightness,
            $alpha
        );
    }
}
