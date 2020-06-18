<?php
namespace PackageFactory\ColorHelper\Eel;

use Neos\Eel\ProtectedContextAwareInterface;
use PackageFactory\ColorHelper\Library\Mexitek\PHPColors\Color as ColorLibrary;

class Color implements ProtectedContextAwareInterface
{

    /**
     * Default adjustments for color manipulations
     */
    const DEFAULT_ADJUSTMENT = 10;

    private $red = 0;
    private $green = 0;
    private $blue = 0;

    private $hue = 0;
    private $saturation = 0;
    private $lightness = 0;

    private $alpha = 1;

    /**
     * @param int $hue
     * @param int $saturation
     * @param int $lightness
     * @param float $alpha
     * @return Color
     * @throws \Exception
     */
    public static function createFromHSL(int $hue, int $saturation, int $lightness, float $alpha = 1): self
    {
        $hue = $hue % 360;
        if ($hue < 0) {
            $hue += 360;
        }

        $saturation = self::limitNumber($saturation, 0, 100);
        $lightness = self::limitNumber($lightness, 0, 100);
        $alpha = self::limitNumber($alpha, 0, 1);

        $hsl = [
            'H' => $hue,
            'S' => $saturation / 100,
            'L' => $lightness / 100
        ];
        $hex = ColorLibrary::hslToHex($hsl);
        $rgb = ColorLibrary::hexToRgb($hex);

        $color = new static();
        $color->red = $rgb['R'];
        $color->green = $rgb['G'];
        $color->blue = $rgb['B'];
        $color->hue = $hue;
        $color->saturation = $saturation;
        $color->lightness = $lightness;
        $color->alpha = $alpha;
        return $color;
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param float $alpha
     * @return Color
     * @throws \Exception
     */
    public static function createFromRgb(int $red, int $green, int $blue, float $alpha = 1): self
    {
        $red = self::limitNumber($red, 0, 255);
        $green = self::limitNumber($green, 0, 255);
        $blue = self::limitNumber($blue, 0, 255);
        $alpha = self::limitNumber($alpha, 0, 1);

        $rgb = [
            'R' => $red,
            'G' => $green,
            'B' => $blue
        ];
        $hex = ColorLibrary::rgbToHex($rgb);
        $hsl = ColorLibrary::hexToHsl($hex);

        $color = new static();
        $color->red = $rgb['R'];
        $color->green = $rgb['G'];
        $color->blue = $rgb['B'];
        $color->hue = round($hsl['H']);
        $color->saturation = round($hsl['S'] * 100);
        $color->lightness = round($hsl['L'] * 100);
        $color->alpha = $alpha;

        return $color;
    }

    /**
     * @param string $hex
     * @return Color
     */
    public static function createFromHex(string $hex): self
    {
        $hsl = ColorLibrary::hexToHsl($hex);
        $rgb = ColorLibrary::hexToRgb($hex);

        $color = new static();
        $color->red = $rgb['R'];
        $color->green = $rgb['G'];
        $color->blue = $rgb['B'];
        $color->hue = round($hsl['H']);
        $color->saturation = round($hsl['S'] * 100);
        $color->lightness = round($hsl['L'] * 100);

        return $color;
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
    public function getAlpha(): float
    {
        return $this->alpha;
    }

    public function __toString(): string
    {
        if ($this->alpha == 1) {
            return $this->hex();
        } else {
            return $this->rgb();
        }
    }

    public function rgb(): string
    {
        if ($this->alpha == 1) {
            return sprintf('rgb( %s, %s, %s)', $this->red, $this->green, $this->blue);
        } else {
            return sprintf('rgba( %s, %s, %s, %s)', $this->red, $this->green, $this->blue, $this->alpha);
        }
    }

    public function hsl(): string
    {
        if ($this->alpha == 1) {
            return sprintf('hsl( %s, %s%%, %s%%)', $this->hue, $this->saturation, $this->lightness);
        } else {
            return sprintf('hsla( %s, %s%%, %s%%, %s)', $this->hue, $this->saturation, $this->lightness, $this->alpha);
        }
    }

    public function hex(): string
    {
        return '#' . ColorLibrary::rgbToHex([
                'R' => $this->red,
                'G' => $this->green,
                'B' => $this->blue
        ]);
    }

    /**
     * @param Color $color
     * @param int $weight between 0 and 100
     * @return string
     */
    public function mix(Color $color, int $weight = 50): self
    {
        $weight = $this->limitNumber($weight, 0, 100);
        $factorA = $weight / 100;
        $factorB = 1 - $factorA;

        return self::createFromRgb(
            round(($this->red * $factorA) + ($color->getRed() * $factorB)),
            round(($this->green * $factorA) + ($color->getGreen() * $factorB)),
            round(($this->blue * $factorA) + ($color->getBlue() * $factorB)),
            round(($this->alpha * $factorA) + ($color->getAlpha() * $factorB))
        );
    }

    /**
     * @param int $amount between 0 and 100
     * @return string
     */
    public function lighten(int $amount = self::DEFAULT_ADJUSTMENT ): self
    {
        return self::createFromHSL(
            $this->hue,
            $this->saturation,
            $this->lightness + $amount,
            $this->alpha
        );
    }

    /**
     * @param int $amount between 0 and 100
     * @return string
     */
    public function darken(int $amount = self::DEFAULT_ADJUSTMENT ): self
    {
        return self::createFromHSL(
            $this->hue,
            $this->saturation,
           $this->lightness - $amount,
            $this->alpha
        );
    }


    /**
     * Adjust the value by rotating the hue angle of a color in either direction.
     *
     * @param int $amount degrees to rotate the color
     * @return string
     */
    public function spin(int $amount): self
    {
        return self::createFromHSL(
            $this->hue + $amount,
            $this->saturation,
            $this->lightness,
            $this->alpha
        );
    }

    /**
     * @param int $amount to saturate the color
     * @return string
     */
    public function saturate(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return self::createFromHSL(
           $this->hue,
            self::limitNumber($this->saturation + $amount, 0, 100),
           $this->lightness,
           $this->alpha
        );
    }

    /**
     * @param int $amount to desaturate the color
     * @return string
     */
    public function desaturate(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return self::createFromHSL(
            $this->hue,
            self::limitNumber($this->saturation - $amount, 0, 100),
            $this->lightness,
            $this->alpha
        );
    }

    /**
     * @param int $amount to desaturate the color
     * @return string
     */
    public function fadein(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return self::createFromRgb(
            $this->red,
            $this->green,
            $this->blue,
            $this->alpha + ($amount / 100)
        );
    }


    /**
     * @param int $amount to desaturate the color
     * @return string
     */
    public function fadeout(int $amount = self::DEFAULT_ADJUSTMENT): self
    {
        return self::createFromRgb(
            $this->red,
            $this->green,
            $this->blue,
            $this->alpha - ($amount / 100)
        );
    }

    /**
     * @param int|float|double $value
     * @param int $min
     * @param int $max
     * @return int
     */
    protected static function limitNumber($value, int $min = 0, int $max = 1) {
        if ($value < $min) {
            return $min;
        } elseif ($value > $max) {
            return $max;
        } else {
            return $value;
        }
    }

    /**
     * @param string $methodName
     * @return bool
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }

}
